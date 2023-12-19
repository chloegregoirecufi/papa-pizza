<?php

namespace App\Repository;

use Core\Repository\Repository;

class OrderRepository extends Repository
{
    public function getTableName(): string
    {
        return 'order';
    }

    public function insertOrder(array $data)
    {
        $query = sprintf(
            'INSERT INTO `%s` (`order_number`,`date_order`, `status`, `user_id`)
            VALUES (:order_number, :date_order, :status, :user_id) ',
            $this->getTableName()
        );

        //on prépare la requete 
        $stmt = $this->pdo->prepare($query);

        //on vérifie si la requete s'est bien preparée
        if (!$stmt) return false;

        //on execute la requete en bindant les param
        $stmt->execute($data);

        //on regarde si au moins un eligne a été enregistrée
        return $this->pdo->lastInsertId();
    }

    public function takeOrder(int $id)
    {
        $query = sprintf(
            'SELECT * FROM `%s` WHERE id= :id ',
            $this->getTableName()
        );

        //on prépare la requete 
        $stmt = $this->pdo->prepare($query);

        //on vérifie si la requete s'est bien preparée
        if (!$stmt) return false;

        //on execute la requete en bindant les param
        $stmt->execute(['id' => $id]);
    }

    public function findOrderIdByUser(int $id)
    {
        $query = sprintf(
            'SELECT id FROM `%s` WHERE user_id = :id',
            $this->getTableName()
        );

        //on prépare la requete 
        $stmt = $this->pdo->prepare($query);

        //on vérifie si la requete s'est bien preparée
        if (!$stmt) return false;

        //on execute la requete en bindant les param
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }
}
