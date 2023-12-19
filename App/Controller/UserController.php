<?php

namespace App\Controller;

use App\Model\User;
use Core\View\View;
use App\Model\Order;
use App\Model\Pizza;
use App\AppRepoManager;
use App\Model\Order_row;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;

class UserController extends Controller
{
    public function account(int $id)
    {
        $view = new View('user/account');

        $view->render();
    }

    //Méthode qui recupère les information personnel
    public function compte(int $id)
    {
        //on vérifie que l'utilisateur est connecté 
        if (!AuthController::isAuth()) self::redirect('/');

        $user = AppRepoManager::getRm()->getUserRepository()->findUserById($id);
        $data_view = [
            'user' => $user,
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view = new View('user/compte');

        $view->render($data_view);
    }

    //méthode qui retourne le formulaire d'ajout d'un membre d'équipe
    public function modification(int $id)
    {
        //on vérifie que l'utilisateur est connecté 
        if (!AuthController::isAuth()) self::redirect('/');

        $view = new View('user/modification');

        $user = AppRepoManager::getRm()->getUserRepository()->findUserById($id);

        $view_data = [
            'user' => $user,
            //permet de recupérer les message d'erreurs du formulaire (s'il y en a)
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view->render($view_data);
    }

    //méthode qui recoit le formulaire d'ajout des modification des users
    public function registerInfoUser(ServerRequest $request)
    {
        //on vérifie que l'utilisateur est connecté 
        if (!AuthController::isAuth()) self::redirect('/');

        $data_form = $request->getParsedBody();
        //var_dump($data_form);
        $form_result = new FormResult();
        $user = new User();

        //on verifie que tous les champs sont remplis 
        if (
            empty($data_form['lastname']) ||
            empty($data_form['firstname']) ||
            empty($data_form['email']) ||
            empty($data_form['phone']) ||
            empty($data_form['address']) ||
            empty($data_form['zip_code']) ||
            empty($data_form['city'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else if ($data_form['lastname'] !== $data_form['lastname']) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else if ($data_form['firstname'] !== $data_form['firstname']) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else if ($data_form['email'] !== $data_form['email']) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else if ($data_form['phone'] !== $data_form['phone']) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else if ($data_form['address'] !== $data_form['address']) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else if ($data_form['zip_code'] !== $data_form['zip_code']) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else if ($data_form['city'] !== $data_form['city']) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } else {
            //on peut enregistrer notre nouvel user
            //J'ai fait le choix de mettre trim sur certain éléments au cas ou il y est un redirection possible sur une app de maps du coté de l'admin
            $data_user = [
                'id' => intval($data_form['id']),
                'lastname' => $this->validInputUser($data_form['lastname']),
                'firstname' => $this->validInputUser($data_form['firstname']),
                'email' => strtolower($this->validInputUser($data_form['email'])),
                'phone' => $this->validInputUser($data_form['phone']),
                'address' => trim($this->validInputUser($data_form['address'])),
                'zip_code' => trim($this->validInputUser($data_form['zip_code'])),
                'city' => trim($this->validInputUser($data_form['city']))
            ];

            $user = AppRepoManager::getRm()->getUserRepository()->modifUser($data_user);
            $this->redirect('/user/compte/' . $user->id);
        }
    }

    //methode qui format les inputs des formulaire
    public function validInputUser(string $value)
    {
        //on met toute la string en minuscule 
        $value = strtolower($value);
        //on supprime les espaces en début et en fin de string 
        $value = trim($value);

        return $value;
    }

    //méthode qui recoit les commandes dans le panier
    public function panier(int $id)
    {
        //on vérifie que l'utilisateur est connecté 
        if (!AuthController::isAuth()) self::redirect('/');

        $view_data = [
            'order' => AppRepoManager::getRm()->getOrderRepository()->takeOrder($id),
            'order_row' => AppRepoManager::getRm()->getOrderRowRepository()->takeOrderRow($id)
        ];

        $view = new View('user/panier');

        $view->render($view_data);
    }

    //méthode qui recoit le formulaire d'ajout des pizzas dans le panier
    public function addPizzaPanier(ServerRequest $request)
    {
        //on vérifie que l'utilisateur est connecté 
        if (!AuthController::isAuth()) self::redirect('/');

        $post_data = $request->getParsedBody();
        $form_result = new FormResult();


        if (
            empty($post_data['user_id']) ||
            empty($post_data['price']) ||
            empty($post_data['pizza_id']) ||
            empty($post_data['quantity'])
        ) {
            $form_result->addError(new FormError('Veuillez remplir tous les champs'));
        } else {
            $user_id = intval($post_data['user_id']);
            $quantity = intval($post_data['quantity']);
            $price = floatval($post_data['price'] * $quantity);
            $pizza_id = intval($post_data['pizza_id']);
            $order_number = uniqid();
            date_default_timezone_set('Europe/Paris');
            $date = date('Y-m-d_H:i');;
            $status = 'en cour';


            $data_order = [
                'order_number' => $order_number,
                'date_order' => $date,
                'status' => $status,
                'user_id' => $user_id
            ];

            $order_id = AppRepoManager::getRm()->getOrderRepository()->insertOrder($data_order);
            if (!$order_id) {
                $form_result->addError(new FormError('Error lors de création de commande'));
            }

            $data_order_row = [
                'quantity' => $quantity,
                'price' => $price,
                'order_id' => $order_id,
                'pizza_id' => $pizza_id
            ];

            $data_order_row_id = AppRepoManager::getRm()->getOrderRowRepository()->insertOrderRow($data_order_row);
            if (!$data_order_row_id) {
                $form_result->addError(new FormError('Error lors de création de commande'));
            }
        }

        //si il y a des erreurs
        if ($form_result->hasErrors()) {
            //on stocke les erreurs dans la session
            Session::set(Session::FORM_RESULT, $form_result);
            //on redirige vers la page d'ajout de jouet
            self::redirect("/pizza/$pizza_id");
        }
        //sinon on redirige vers la page admin
        Session::remove(Session::FORM_RESULT);
        self::redirect("/pizza/$pizza_id");
    }


    //méthode qui fretourne la vue commande pizza
    public function commandesUser(int $id)
    {
        //on vérifie que l'utilisateur est connecté 
        if (!AuthController::isAuth()) self::redirect('/');

        $view = new View('user/commandes');

        $view->render();
    }





    public function listPizza(int $id)
    {
        //on vérifie que l'utilisateur est connecté 
        if (!AuthController::isAuth()) self::redirect('/');


        $view_data = [
            'pizzas' => AppRepoManager::getRm()->getPizzaRepository()->getAllPizzasByUser($id),
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view = new View('user/list-pizza');

        $view->render($view_data);
    }


    //Méthode qui récupère la liste des pizzas
    public function getPizzas()
    {
        $view_data = [
            'pizzas' => AppRepoManager::getRm()->getPizzaRepository()->getAllPizzas()
        ];

        $view = new View('home/pizzas');

        $view->render($view_data);
    }


    //méthode qui nous renvoi sur la vue utilisateur  
    public function home()
    {
        //on vérifie que l'utilisateur est connecté 
        if (!AuthController::isAuth()) self::redirect('/');

        $view = new View('user/home');

        $view->render();
    }

    //méthode qui reçoit le formulaire d'ajout de pizza
    public function addPizzaForm(ServerRequest $request)
    {
        //on vérifie que l'utilisateur est connecté 
        if (!AuthController::isAuth()) self::redirect('/');

        $post_data = $request->getParsedBody();
        $form_result = new FormResult();
        $pizza = new Pizza();



        if (
            //on vérifie que les autres champs sont remplis
            empty($post_data['name']) ||
            empty($post_data['user_id']) ||
            empty($post_data['ingredients']) ||
            empty($post_data['size_id'])
        ) {
            $form_result->addError(new FormError('Veuillez remplir tous les champs'));
        } elseif (count($post_data['ingredients']) > 8) {
            $form_result->addError(new FormError('Veuillez choisir moins de 8 ingrédients'));
        } else {
            $size_id = intval($post_data['size_id']);
            $name = trim($post_data['name']);
            $array_ingredients = $post_data['ingredients'];
            $user_id = intval($post_data['user_id']);

            $size_price = 0;

            if ($size_id == 1) {
                $size_price = 6;
            } elseif ($size_id == 2) {
                $size_price = 7.50;
            } else {
                $size_price = 9;
            }

            $final_price = $size_price + (count($array_ingredients) * 1);
            var_dump($final_price);

            $data_pizza = [
                'name' => $this->validInputUser($post_data['name']),
                'user_id' => $this->validInputUser($post_data['user_id']),
                'is_active' => 1,
                'image_path' => '',
            ];

            $pizza = AppRepoManager::getRm()->getPizzaRepository()->insertPizza($data_pizza);
            if (!$pizza) {
                $form_result->addError(new FormError('Erreur lors de l\'insertion de la pizza'));
            } else {
                //on récupère l'id de la pizza
                $pizza_id = $pizza->id;
                //on va insérer les ingrédients de la pizza
                foreach ($array_ingredients as $ingredient_id) {
                    //on crée un tableau de données
                    $data_pizza_ingredient = [
                        'pizza_id' => intval($pizza_id),
                        'ingredient_id' => intval($ingredient_id),
                        'unit_id' => 5,
                        'quantity' => count($post_data['ingredients']),
                    ];
                    //on appelle la méthode qui va insérer les ingrédients de la pizza
                    $pizza_ingredient = AppRepoManager::getRm()->getPizzaIngredientRepository()->insertPizzaIngredient($data_pizza_ingredient);
                    //on vérifie que l'insertion s'est bien passée
                    if (!$pizza_ingredient) {
                        $form_result->addError(new FormError('Erreur lors de l\'insertion des ingrédients de la pizza'));
                    }
                }
                //on crée un tableau de données
                $data_price = [
                    'pizza_id' => intval($pizza_id),
                    'size_id' => intval($size_id),
                    'price' => floatval($final_price)
                ];
                //on appelle la méthode qui va insérer les tailles de la pizza
                $price = AppRepoManager::getRm()->getPriceRepository()->insertPrice($data_price);
                //on vérifie que l'insertion s'est bien passée
                if (!$price) {
                    $form_result->addError(new FormError('Erreur lors de l\'insertion des tailles de la pizza'));
                }
            }
        }
        $this->redirect('/user/pizza/list/' . $post_data['user_id']);
    }


    //méthode qui retourne le formulaire d'ajout de pizza
    public function addPizza()
    {
        $view_data = [
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view = new View('user/add-pizza');

        $view->render($view_data);
    }
}
