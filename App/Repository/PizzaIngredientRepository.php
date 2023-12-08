<?php 

namespace App\Repository;

use App\AppRepoManager;
use App\Model\Ingredient;
use Core\Repository\Repository;

class PizzaIngredientRepository extends Repository
{
    public function getTableName(): string
    {
        return ' pizza_ingredient';
    }

    //méthode qui recupère la liste des ingrédients d'une pizza
    public function getIngredientByPizzaId(int $pizza_id)
    {
        $array_result = [];

        /*'SELECT i.* FROM pizza_ingredient AS pi
        INNER JOIN ingredient AS i ON pi.ingredientid = i.id
        WHERE pi.pizza_id = 8',*/
        
        //':' paramètre dynamique qui permet que quand on selectionne une pizza ça change ses id
        $query = sprintf(
            'SELECT *
            FROM %1$s AS pi 
            INNER JOIN %2$s AS i ON pi.ingredient_id = i.id
            WHERE pi.pizza_id=:id',
            $this->getTableName(), 
            AppRepoManager::getRm()->getIngredientRepository()->getTableName()
        );

        //on prépare le requete 
        $stmt = $this->pdo->prepare($query);

        //on verifie si la requete s'est bien préparée
        if(!$stmt) return $array_result;

        //on execute la requete en bindant les parametres
        $stmt->execute(['id' => $pizza_id]);

        //on récupère les résultats 
        while($row_data = $stmt->fetch()){
            $array_result[] = new Ingredient($row_data);
        }

        return $array_result;
    }
}