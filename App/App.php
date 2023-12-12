<?php

namespace App;

use MiladRahimi\PhpRouter\Router;
use App\Controller\AuthController;
use App\Controller\UserController;
use App\Controller\AdminController;
use App\Controller\PizzaController;
use Core\Database\DatabaseConfigInterface;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use MiladRahimi\PhpRouter\Exceptions\InvalidCallableException;

class App implements DatabaseConfigInterface
{
    //on déclare des constantes pour la connexion à la base de données
    private const DB_HOST = 'database';
    private const DB_NAME = 'papapizza';
    private const DB_USER = 'admin';
    private const DB_PASS = 'admin';

    //on déclare une propriété privée qui va contenir une intance de app
    //Design pattern Singleton
    private static ?self $instance = null;

    //méthode public appelé au démarrage de l'application dans index.php
    public static function getApp():self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //On va configurer notre router
    private Router $router;

    public function getRouter(): Router
    {
        return $this->router;
    }

    private function __construct()
    {
        $this->router = Router::create();
    }

    //on aura 3 méthodes à définir pour le router
    //1: méthode start() qui va démarrer le router dans l'application
    public function start(): void
    {
        //on ouvre l'accès à la session
        session_start();
        //on enregistre les routes
        $this->registerRoutes();
        //on démarre le router
        $this->startRouter();
    }

    //2: méthode qui enregistre les routes
    private function registerRoutes()
    {
        //exemple de routes avec un controller
        // $this->router->get('/', [ToyController::class, 'index']);
        $this->router->get('/', [PizzaController::class, 'home']);
        $this->router->get('/pizzas' , [PizzaController::class, 'getPizzas']);
        $this->router->get('/pizza/{id}', [PizzaController::class, 'getPizzaById']);
        //route pour le formulaire de login
        $this->router->get('/connexion', [AuthController::class, 'loginForm']);
        //route qui recoit le formulaire de login
        $this->router->post('/login', [AuthController::class, 'login']);
        //route pour le formulaire d'inscription
        $this->router->get('/inscription', [AuthController::class, 'registerForm']);
        //route qui recoit le formulaire de création de compte
        $this->router->post('/register', [AuthController::class, 'register']);
        //route pour acceder au compte de l'utilisateur
        $this->router->get('/account/{id}', [UserController::class, 'account']);
        //route pour se déconnecter
        $this->router->get('/logout', [AuthController::class, 'logout']);

        /*PARTIE BACK OFFICE*/
        //route pour acceder à l'interface admin
        $this->router->get('/admin/home', [AdminController::class, 'home']);
        $this->router->get('/admin/user/list', [AdminController::class, 'listUser']);
        $this->router->get('/admin/team/list', [AdminController::class, 'listTeam']);
        $this->router->get('/admin/pizza/list', [AdminController::class, 'listPizza']);
        $this->router->get('/admin/order/list', [AdminController::class, 'listOrder']);
       


        //route pour supprimer un user
        $this->router->get('/admin/user/delete/{id}', [AdminController::class, 'deleteUser']);
        //route pour ajouter un membre d'équipe 
        $this->router->get('/admin/team/add', [AdminController::class, 'addTeam']);
        //route qui recevra le formulaire d'ajout d'un membre d'équipe
        $this->router->post('/register-team', [AuthController::class, 'registerTeam']);
        //route pour ajouter une pizza
        $this->router->get('/admin/pizza/add', [AdminController::class, 'addPizza']);
        //route qui receptionne les données
        $this->router->post('/add-pizza-form', [AdminController::class, 'addPizzaForm']);
    }

    //3: méthode qui va démarrer le router
    private function startRouter()
    {
        try {
            $this->router->dispatch();
        } catch (RouteNotFoundException $e) {
            //TODO : gérer la vue 404
            var_dump('Erreur 404: ' . $e);
        } catch (InvalidCallableException $e) {
            //TODO : gérer la vue 500
            var_dump('Erreur 500: ' . $e);
        }
    }

    //on déclare nos méthodes imposé par l'interface DatabaseConfigInterface
    public function getHost(): string
    {
        return self::DB_HOST;
    }

    public function getName(): string
    {
        return self::DB_NAME;
    }

    public function getUser(): string
    {
        return self::DB_USER;
    }

    public function getPass(): string
    {
        return self::DB_PASS;
    }
}
