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

    //methode qui modifie les données de user
    public function modifUser(array $data): ? User
    {
       $data_more = [
        'is_admin' => 0,
        'is_active' => 1,
       ];
       //on fusionne les 2 tableaux 
       //$data = array_merge($data, $data_more);
       //die();
       
       //on créer la requete 
       $query = sprintf(
           'UPDATE %s SET 
            `lastname` =:lastname, 
            `firstname` =:firstname, 
            `email` =:email, 
            `phone` =:phone, 
            `address` =:address, 
            `zip_code` =:zip_code, 
            `city` =:city
        WHERE `id` =:id',
        $this->getTableName()
        );
  

       //on prépare le requete
       $stmt = $this->pdo->prepare($query);
       //on vérifie que la requete est bien préparée 
       if(!$stmt) return null;

       //on execute la requete
       $stmt->execute($data);

       //on recupere l'utilisateur grace à cette id
       return $this->readById(User:: class, $data['id']);
       
    }


    //méthode qui récupère un user par son id
    public function findUserById(int $id): ?User
    {
        return $this->readById(User::class, $id);    
    }

    //méthode qui recup les user non admin et qui sont actif
    public function getAllClientsActif(): array
    {
        //on déclare un tableau vide
        $users = [];
        //on créer la requete 
        $query = sprintf(
            'SELECT * FROM %s WHERE is_admin=0 AND is_active=1',
            $this->getTableName()
        );

        //on peut executer directement la requete
        $stmt = $this->pdo->query($query);

        //on vérifie que la requete est bien executer
        if(!$stmt) return $users;

        //on recupere les resultats
        while($result = $stmt->fetch())
        {
            $users[] = new User($result);
        }
        
        return $users;
    }

    //méthode qui recupère tous les employée
    public function getAllTeamActif(): array
    {
        //on déclare un tableau vide
        $users = [];
        //on créer la requete 
        $query = sprintf(
            'SELECT * FROM %s WHERE is_admin=1 AND is_active=1',
            $this->getTableName()
        );

        //on peut executer directement la requete
        $stmt = $this->pdo->query($query);

        //on vérifie que la requete est bien executer
        if(!$stmt) return $users;

        //on recupere les resultats
        while($result = $stmt->fetch())
        {
            $users[] = new User($result);
        }
        
        return $users;
    }

    //méthode qui désactive un user
    public function deleteUser(int $id): bool 
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

    //méthode qui ajoute un employé
    public function addTeam(array $data): ?User
    {
        $data_more = [
         'is_admin' => 1,
         'is_active' => 1,
        ];
        //on fusionne les 2 tableaux 
        $data = array_merge($data, $data_more);
 
        //on créer la requete 
        $query = sprintf(
         'INSER INTO %s (`email`, `password`, `lastname`, `firstname`, `phone`, `is_admin`, `is_active`)
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

    //méthode qui ajoute une pizza personnalisé 
    public function addPizzaCustom(array $data)
    {
        $data_more = [
            'is_admin' => 0,
            'is_active' => 1,
           ];
           //on fusionne les 2 tableaux 
           $data = array_merge($data, $data_more);
    
           //on créer la requete 
           $query = sprintf(
            'INSERT INTO %s (`user_id`, `name`, `ingredients`, `size_id`)
            VALUES (:user_id, :name, :ingredients, :size_id)',
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
 
}