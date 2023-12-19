<?php

namespace App\Repository;

use Core\Repository\Repository;

class OrderRowRepository extends Repository
{
    public function getTableName(): string
    {
        return 'order_row';
    }

    public function insertOrderRow(array $data): bool
    {
        $query = sprintf(
            'INSERT INTO %s (`quantity`,`price`, `order_id`, `pizza_id`)
            VALUES (:quantity, :price, :order_id, :pizza_id) ',
            $this->getTableName()
        );

        //on prépare la requete 
        $stmt = $this->pdo->prepare($query);

        //on vérifie si la requete s'est bien preparée
        if (!$stmt) return false;

        //on execute la requete en bindant les param
        return $stmt->execute($data);
    }

    public function takeOrderRow(int $id)
    {
        $query = sprintf('SELECT * FROM `%s` WHERE order_id = :id',
        $this->getTableName()
);

        //on prépare la requete 
        $stmt = $this->pdo->prepare($query);

        //on vérifie si la requete s'est bien preparée
        if (!$stmt) return false;

        //on execute la requete en bindant les param
        $stmt->execute(['id'=> $id]);
    }
}
