<?php
namespace App\Repository;

use App\AppRepoManager;
use App\Model\Pizza;
use Core\Repository\Repository;

class PizzaRepository extends Repository
{
    public function getTableName():string
    {
        return 'pizza';
    }

    //on créer une méthode qui va récupérer toutes les pizzas
    public function getAllPizzas(): array
    {
        //on déclare un tableau vide
        $array_result = [];

        //on déclare la requête SQL
        $query = sprintf(
            'SELECT p.`id`, p.`name`, p.`image_path`
            FROM %1$s AS p
            INNER JOIN %2$s AS u ON p.`user_id`=u.`id`
            WHERE u.`is_admin` = 1 
            AND p.`is_active` = 1',
            $this->getTableName(),
            AppRepoManager::getRm()->getUserRepository()->getTableName()

        );

        //on peut directement executer la requete avec la méthode query()
        $stmt = $this->pdo->query($query);
        //on vérifie si la requête s'est bien exécutée
        if(!$stmt) return $array_result;

        //on récupère les données de la table dans une boucle
        while($row_data = $stmt->fetch()){
            $array_result[] = new Pizza($row_data);
        }

        return $array_result;
    }

    //on créer une méthode qui va récupérer toutes les pizzas avec ses infos
    public function getAllPizzasWithInfo(): array
    {
        //on déclare un tableau vide
        $array_result = [];

        //on déclare la requête SQL
        $query = sprintf(
            'SELECT p.`id`, p.`name`, p.`image_path`
            FROM %1$s AS p
            INNER JOIN %2$s AS u ON p.`user_id`=u.`id`
            WHERE u.`is_admin` = 1 
            AND p.`is_active` = 1',
            $this->getTableName(),
            AppRepoManager::getRm()->getUserRepository()->getTableName()

        );

        //on peut directement executer la requete avec la méthode query()
        $stmt = $this->pdo->query($query);
        //on vérifie si la requête s'est bien exécutée
        if(!$stmt) return $array_result;

        //on récupère les données de la table dans une boucle
        while($row_data = $stmt->fetch()){
            $pizza = new Pizza($row_data);

            $pizza->ingredients = AppRepoManager::getRm()->getPizzaIngredientRepository()->getIngredientByPizzaId($pizza->id);
            $pizza->prices = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaId($pizza->id);

            $array_result[] = $pizza;
        }

        return $array_result;
    }

    //on créer une méthode qui va récuperer toutes les pizzas que l'utilisateur à créer
    public function getAllPizzasByUser($id): array
    {
        //on déclare un tableau vide
        $array_result = [];

        //on déclare la requête SQL
        $query = sprintf(
            'SELECT p.`id`, p.`name`, p.`image_path`
            FROM %1$s AS p
            INNER JOIN %2$s AS u ON p.`user_id`=u.`id`
            WHERE p.`is_active` = 1
            AND p.`user_id` = :id',
            $this->getTableName(),
            AppRepoManager::getRm()->getUserRepository()->getTableName()

        );

        //on peut directement executer la requete avec la méthode query()
        $stmt = $this->pdo->prepare($query);
        //on vérifie si la requête s'est bien exécutée
        if(!$stmt) return $array_result;
        $stmt->execute([
            'id' => $id,
        ]);

        //on récupère les données de la table dans une boucle
        while($row_data = $stmt->fetch()){
            $pizza = new Pizza($row_data);

            $pizza->ingredients = AppRepoManager::getRm()->getPizzaIngredientRepository()->getIngredientByPizzaId($pizza->id);
            $pizza->prices = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaId($pizza->id);

            $array_result[] = $pizza;
        }

        return $array_result;
    }

    //on créer une méthode qui va récup une pizza par son id
    public function getPizzaById(int $pizza_id): ?Pizza
    {
        //on créer le requete
        $query = sprintf(
            'SELECT * FROM %s WHERE `id`=:id',
            $this->getTableName()
        );

        //on prépare le requete 
        $stmt = $this->pdo->prepare($query);

        //on verifie si la requete s'est bien préparée
        if(!$stmt) return null;
        
        //on execute la requete en bindant les parametres
        $stmt->execute(['id' => $pizza_id]);

        //on récupère le resultat 
        $result = $stmt->fetch();

        //si je n'ai pas de resultats on retourne null
        if(!$result) return null;

        //on retourne une nouvelle instance de Pizza
        $pizza = new Pizza($result);

        //on va hydrater les ingredients de la pizza
        $pizza->ingredients = AppRepoManager::getRm()->getPizzaIngredientRepository()->getIngredientByPizzaId($pizza->id);
        //on va hydrater les prix de la pizza
        $pizza->prices = AppRepoManager::getRm()->getPriceRepository()->getPriceByPizzaId($pizza->id);


        return $pizza; 
    }

    //méthode pour créer une pizza
    public function insertPizza(array $data): ?Pizza
    {
        //on créer le requete 
        $query = sprintf(
            'INSERT INTO %s (name, image_path, is_active, user_id)
            VALUE (:name, :image_path, :is_active, :user_id)',
            $this->getTableName()
        );

        //on préare la requete
        $stmt = $this->pdo->prepare($query);

        //on verifie si la requete s'est bien preparée
        if(!$stmt) return null;
        
        //on execute la requete en bindant les parametre
        $stmt->execute($data);

        //on récupère l'ide de la pizza fraichement crée
        $pizza_id = $this->pdo->lastInsertId();

        //on retourne la pizza
        return $this->getPizzaById($pizza_id);

    }

    //méthode qui désactive une pizza
    public function deletePizza(int $id): bool 
    {
        //on créer la requete
         $query = sprintf(
            'UPDATE %s SET `is_active` = 0 WHERE `id` =:id',
             $this->getTableName()
        );

        //on prépare la requete
        $stmt = $this->pdo->prepare($query);

        //on verifie que la requete est bien préparée
        if (!$stmt) return false;

        //on execute la requete si la requete est passé on retourne true sinon false
        return $stmt->execute(['id' => $id]);
            
    }


   
}