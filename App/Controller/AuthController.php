<?php

namespace App\Controller;

use App\AppRepoManager;
use App\Model\User;
use Core\View\View;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;

class AuthController extends Controller
{
    //methode qui verifie que l'email est valide
    public function validEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    //methode qui verifie que le mdp est valide 
    //(au moins 8 caactères, 1 maj, 1 min, 1 chiffre)
    public function validPassword(string $password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})/', $password);
    }

    public function userExist(string $email): bool
    {
        $user = AppRepoManager::getRm()->getUserRepository()->findUserByEmail($email);
        return !is_null($user);
    }

    //methode qui format les inputs des formulaire
    public function validInput(string $value)
    {
        //on met toute la string en minuscule 
        $value = strtolower($value);
        //on supprime les espaces en début et en fin de string 
        $value = trim($value);
        //on supprime les balises html
        $value = strip_tags($value);
        //on supprime les antislash
        $value = stripslashes($value);
        //on supprime les caracteres speciaux 
        $value = htmlspecialchars($value);

        return $value;
    }


    //méthode qui renvoi sur la vue du formulaire de connexion
    public function loginForm()
    {
        $view = new View('auth/login');

        $view_data = [
            //permet de recupérer les message d'erreurs du formulaire (s'il y en a)
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view->render($view_data);
    }

    //méthode qui renvoi sur la vue du formulaire d'inscription
    public function registerForm()
    {
        $view = new View('auth/register');

        $view_data = [
            //permet de recupérer les message d'erreurs du formulaire (s'il y en a)
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view->render($view_data);
    }



    //méthode qui receptionne les données du formulaire de connexion
    public function login(ServerRequest $request)
    {
        //on récupère les données du formulaire sous forme de tableau associatif
        $post_data = $request->getParsedBody();
        //on instancie notre class FormResult (elle s'occupe de stocker les messages d'erreur dans la session)
        $form_result = new FormResult();
        //on instancie un User
        $user = new User();

        //on vérifie que les champs soient remplis
        if (empty($post_data['email']) || empty($post_data['password'])) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else {
            $email = strtolower(($this->validInput($post_data['email'])));
            //on va vérifier que l'email existe en bdd
            $user = AppRepoManager::getRm()->getUserRepository()->findUserByEmail($email);
            //si on a un retour négatif
            if (is_null($user)) {
                $form_result->addError(new FormError('Email et/ou mot de passe incorrect'));
            } else {
                //si on a un retour positif on vérifie que le mdp est correct
                if (!password_verify($this->validInput($post_data['password']), $user->password)) {
                    $form_result->addError(new FormError('Email et/ou mdp incorrect'));
                }
            }
        }
        //si on a des erreurson les stock en session et on renvoie vers la page de connexion
        if ($form_result->hasErrors()) {
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/connexion');
        }

        //si tout est OK on stock l'utilisateur en session et on le redirige vers la page d'accueil
        $user->password = '';
        Session::set(Session::USER, $user);
        Session::remove(Session::FORM_RESULT);
        self::redirect('/');
    }

    //méthode qui receptionne les donnes du formulaire d'inscription
    public function register(ServerRequest $request)
    {
        //permet de recevoir les données sous forme de tableau associatif (getParseBody)
        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();

        //on verifie que tous lesc hamps sont remplis 
        if (
            empty($data_form['email']) ||
            empty($data_form['password']) ||
            empty($data_form['password_confirm']) ||
            empty($data_form['lastname']) ||
            empty($data_form['firstname']) ||
            empty($data_form['phone'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else if ($data_form['password'] !== $data_form['password_confirm']) {
            $form_result->addError(new FormError('Les mots de passe ne correspondent pas'));
        } else if (!$this->validEmail($data_form['email'])) {
            $form_result->addError(new FormError('L\'email n\'est pas valide'));
        } else if (!$this->validPassword($data_form['password'])) {
            $form_result->addError(new FormError('Le mdp doit contenir au moins 8 caractères, 14 maj, 1 min et 1 chiffre'));
        } else if ($this->userExist($data_form['email'])) {
            $form_result->addError(new FormError('cet email est deja existant'));
        } else {
            //on peut enregistrer notre nouvel user
            $data_user = [
                'email' => strtolower($this->validInput($data_form['email'])),
                'password' => password_hash($this->validInput($data_form['password']), PASSWORD_BCRYPT),
                'lastname' => $this->validInput($data_form['lastname']),
                'firstname' => $this->validInput($data_form['firstname']),
                'phone' => $this->validInput($data_form['phone'])
            ];

            $user = AppRepoManager::getRm()->getUserRepository()->addUser($data_user);
        }

        //s'il y a des erreurs on les stocks en session et on redirige vers la page d'inscription
        if ($form_result->hasErrors()) {
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/inscription');
        }

        //si tout est ok on enregistre l'utilisateur en session et on redirige vers la page d'acceuil
        //on oublie pas de supprimé le mdp 
        $user->password = '';
        Session::set(Session::USER, $user);
        Session::remove(Session::FORM_RESULT);
        //on redirige vers la page d'(acceuil)
        self::redirect('/');
    }

    //méthode qui recoit le formulaire d'ajout d'un membre d'équipe 
    public function registerTeam(ServerRequest $request)
    {
        //on vérifie que l'utilisateur est connecté et est admin
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

        //permet de recevoir les données sous forme de tableau associatif (getParseBody)
        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();

        //on verifie que tous les champs sont remplis 
        if (
            empty($data_form['email']) ||
            empty($data_form['password']) ||
            empty($data_form['password_confirm']) ||
            empty($data_form['lastname']) ||
            empty($data_form['firstname']) ||
            empty($data_form['phone'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else if ($data_form['password'] !== $data_form['password_confirm']) {
            $form_result->addError(new FormError('Les mots de passe ne correspondent pas'));
        } else if (!$this->validEmail($data_form['email'])) {
            $form_result->addError(new FormError('L\'email n\'est pas valide'));
        } else if (!$this->validPassword($data_form['password'])) {
            $form_result->addError(new FormError('Le mdp doit contenir au moins 8 caractères, 14 maj, 1 min et 1 chiffre'));
        } else if ($this->userExist($data_form['email'])) {
            $form_result->addError(new FormError('cet email est deja existant'));
        } else {
            //on peut enregistrer notre nouvel user
            $data_user = [
                'email' => strtolower($this->validInput($data_form['email'])),
                'password' => password_hash($this->validInput($data_form['password']), PASSWORD_BCRYPT),
                'lastname' => $this->validInput($data_form['lastname']),
                'firstname' => $this->validInput($data_form['firstname']),
                'phone' => $this->validInput($data_form['phone'])
            ];

            $user = AppRepoManager::getRm()->getUserRepository()->modifUser($data_user);
        }

        //s'il y a des erreurs on les stocks en session et on redirige vers la page d'inscription
        if ($form_result->hasErrors()) {
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/admin/team/add');
        }

        //on redirige vers la page d'(acceuil)
        Session::remove(Session::FORM_RESULT);
        self::redirect('/admin/team/list');
    }


    //méthode qui vérifie si le user est connecter
    public static function isAuth(): bool
    {
        return !is_null(Session::get(Session::USER));
    }

    //méthode qui verifie si user est un admin
    public static function isAdmin(): bool
    {
        return Session::get(Session::USER)->is_admin;
    }

    //méthode pour se déconnecter
    public function logout()
    {
        //on detruit la session 
        Session::remove(Session::USER);
        //on redirige vers la page d'accueil 
        self::redirect('/');
    }
}
