<?php 

namespace App\Repository;

use App\Model\Size;
use App\Model\Price;
use App\AppRepoManager;
use Core\Repository\Repository;

class PriceRepository extends Repository
{
    public function getTableName(): string
    {
        return ' price';
    }

     //on créer une méthode qui va récuperer le prix des pizzas
     public function getPriceByPizzaId(int $pizza_id)
     {
         //on déclare un tableau vide
         $array_result = [];

         //on créer la requete
         $query = sprintf(
         'SELECT pr.*, s.label
         FROM %1$s AS pr
         INNER JOIN %2$s AS s ON pr.size_id = s.id
         WHERE pr.pizza_id = :id',
         $this->getTableName(),
         AppRepoManager::getRm()->getSizeRepository()->getTableName()
         );

         //on préare la requete
         $stmt = $this->pdo->prepare($query);

         //on verifie que la requete est bien prep
         if(!$stmt) return $array_result;

         //on execute la requete en bindant les parametres
         $stmt->execute(['id' => $pizza_id]);
 
         //on récupère les résultats 
         while($row_data = $stmt->fetch())
         {
            $price = new Price($row_data);

            //on doit reconstruire un tableau pour creer une instance de Size
            $size_data = [
                'id' => $row_data['size_id'],
                'label' =>$row_data['label']
                ];

            $size = new Size($size_data);
            //on hydrate price avec size
            $price->size = $size;
            
            //on ajoute l'objet price dans le tableau
            $array_result[] = $price;
            
         }
 
         return $array_result;
     }

    //méthode pour créer un prix
    public function insertPrice(array $data): bool
    {
          //on créer la requete 
          $query = sprintf(
            'INSERT INTO %s(`price`, `size_id`, `pizza_id`)
            VALUES (:price, :size_id, :pizza_id)',
            $this->getTableName()
        );

        //on prépare la requete 
        $stmt = $this->pdo->prepare($query);

        //on vérifie si la requete s'est bien preparée
        if(!$stmt) return false;

        //on execute la requete en bindant les param
        $stmt->execute($data);

        //on regarde si au moins un eligne a été enregistrée
        return $stmt->rowCount() > 0;
    }
 
     
}