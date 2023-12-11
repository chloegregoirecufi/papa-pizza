<?php 

namespace App\Repository;

use App\Model\User;
use Core\Repository\Repository;

class UserRepository extends Repository
{
    public function getTableName(): string
    {
        return ' user';
    }

    //méthode qui verifie si le user existe en bdd
    public function findUserByEmail(string $email): ?User
    {
        //on crée la requete
        $query = sprintf(
            'SELECT * FROM %s WHERE email = :email',
            $this->getTableName()
        );

        $stmt = $this->pdo->prepare($query);
        //on verifie que la requet est bien prepare
        if(!$stmt) return null;
        $stmt->execute (['email' => $email]);
        //on recupere le resultat
        while($result = $stmt->fetch()){
            $user = new User($result);
        }

        //return $user ? $user : null;
        return $user ??  null;

    }

    //methode qui ajoute un utilisateur
    public function addUser(array $data): ? User
    {
       $data_more = [
        'is_admin' => 0,
        'is_active' => 1,
       ];
       //on fusionne les 2 tableaux 
       $data = array_merge($data, $data_more);

       //on créer la requete 
       $query = sprintf(
        'INSERT INTO %s (`email`, `password`, `lastname`, `firstname`, `phone`, `is_admin`, `is_active`)
        VALUES (:email, :password, :lastname, :firstname, :phone, :is_admin, :is_active)',
        $this->getTableName()
       );

       //on prépare le requete
       $stmt = $this->pdo->prepare($query);
       //on vérifie que la requete est bien préparée 
       if(!$stmt) return null;

       //on execute la requete
       $stmt->execute($data);

       //on récupère l'id de l'utilisateur qui vient d'etre enregistrer
       $id = $this->pdo->lastInsertId();
       //on recupere l'utilisateur grace à cette id
       return $this->readById(User:: class, $id);
       
    }

    //méthode qui récupère un user par son id
    public function findUserById(int $id): ?User
    {
        return $this->readById(User::class, $id);    
    }
}