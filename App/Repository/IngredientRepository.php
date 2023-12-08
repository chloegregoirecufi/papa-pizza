<?php 

namespace App\Repository;

use App\Model\Ingredient;
use Core\Repository\Repository;

class IngredientRepository extends Repository
{
    public function getTableName(): string
    {
        return ' ingredient';
    }

    //méthode qui récupère la liste des ingrédients actifs
    public function getIngredientActive()
    {
        //on déclare un tableau vide 
        $array_result = [];
        //on créer la requete 
        $query = sprintf(
            'SELECT * FROM %s WHERE is_actuve = 1',
            $this->getTableName()
        );

        //on execute la requete 
        $stmt = $this->pdo->query($query);
        //on vérifie si la requete s'est bien passé
        if(!$stmt) return $array_result;

        //on récupère kes résultats
        while($row_data = $stmt->fetch()){
            $array_result[] = new Ingredient($row_data);
        }

        return $array_result;
    }
}