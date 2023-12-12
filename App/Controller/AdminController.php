<?php 

namespace App\Controller;

use App\AppRepoManager;
use Core\View\View;
use Core\Controller\Controller;
use App\Controller\AuthController;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Laminas\Diactoros\ServerRequest;

class AdminController extends Controller
{
    //méthode qui nous renvoi sur la vue admin 
        public function home()
        {
            //on vérifie que l'utilisateur est connecté et est admin
            if( !AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

            $view = new View('admin/home');
    
            $view -> render();
    
        }


        public function listUser()
        {
            //on vérifie que l'utilisateur est connecté et est admin
            if( !AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');
            
            $users = AppRepoManager::getRm()->getUserRepository()->getAllClientsActif();
            $data_view = [
                'users' => $users,
                'form_result' => Session::get(Session::FORM_RESULT)
            ];

            $view = new View('admin/list-user');

            $view->render($data_view);
        }

        //liste des membres de l'équipe
        public function listTeam()
        {
            //on vérifie que l'utilisateur est connecté et est admin
            if( !AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

            $users = AppRepoManager::getRm()->getUserRepository()->getAllTeamActif();
            $data_view = [
                'users' => $users,
                'form_result' => Session::get(Session::FORM_RESULT)
            ];

            $view = new View('admin/list-team');

            $view->render($data_view);

        }

        public function listPizza()
        {
            //on vérifie que l'utilisateur est connecté et est admin
            if( !AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');
            

            $view_data = [
                'pizzas' => AppRepoManager::getRm()->getPizzaRepository()->getAllPizzasWithInfo(),
                'form_result' => Session::get(Session::FORM_RESULT)
            ];

            $view = new View('admin/list-pizza');

            $view->render($view_data);

        }

        public function listOrder()
        {
            //on vérifie que l'utilisateur est connecté et est admin
            if( !AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

            $view = new View('admin/list-order');

            $view->render();

        }

        //on désactive un user
        public function deleteUser(int $id)
        {
            //on vérifie que l'utilisateur est connecté et est admin
            if( !AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');


            $form_result = new FormResult();
            //on appelle la methode desactive un user
            $deleteUser = AppRepoManager::getRm()->getUserRepository()->deleteUser($id);
            //si la méthode renvoi false on stock un message d'erreur
            if(!$deleteUser){
                $form_result->addError(new FormError('Une erreur est survenue lors de la suppression de l\'utilisateur'));
            }

            //s'il y a des erreurs on les enregistre en session
            if($form_result->hasErrors()) {
                Session::set(Session::FORM_RESULT, $form_result);
                self::redirect('/admin/user/list');
            }
            //si tout est ok on redirige vers la liste utilisateur 
            Session::remove(Session::FORM_RESULT);
            self::redirect('/admin/user/list');
        }

        //méthode qui retourne le formulaire d'ajout d'un membre d'équipe
        public function addTeam()
        {
            //on vérifie que l'utilisateur est connecté et est admin
            if( !AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

            $view = new View('admin/add-team');

            $view_data = [
             //permet de recupérer les message d'erreurs du formulaire (s'il y en a)
             'form_result' => Session::get(Session::FORM_RESULT)
            ];
     
             $view->render($view_data);
     
        }

        //méthode qui retourne le formulaire d'ajout de pizza
        public function addPizza()
        {
            $view_data = [
                'form_result' => Session::get(Session::FORM_RESULT)
            ];

            $view = new View('admin/add-pizza');

            $view->render($view_data);
        }

        //méthode qui recoit le formulaire d'ajout de la pizza
        public function addPizzaForm(ServerRequest $request)
        {
            $post_data = $request->getParsedBody();
            $file_data = $_FILES;
            var_dump($post_data, $file_data);
    
            //on créer des variables
            $name = $post_data['name'];
            $user_id = $post_data['user_id'];
            $array_ingredients = $post_data['ingredients'];
            $array_size = $post_data['size_id'];
            $array_price = $post_data['price'];
            $image_name = $file_data['image_path']['name'];
            $tmp_path = $file_data['image_path']['tmp_name'];
            $final_path = '/assets/images/pizza/';
            $form_result = new FormResult();

            //on verifie que tous les champs sont remplis
            if (
                empty($name) ||
                empty($user_id) ||
                empty($array_ingredients) ||
                empty($array_size) ||
                empty($array_price)
            ) {
                $form_result->addError(new FormError('Tous les chmaps sont obligatoire'));
            }
        }





}


