<?php 

namespace App\Repository;

use App\Model\Size;
use Core\Repository\Repository;

class SizeRepository extends Repository
{
    public function getTableName(): string
    {
        return ' size';
    }

    //méthode qui recupère toutes les tailles
    public function getAllSize()
    {
        return $this->readAll(Size::class);
    }

    //méthode qui atribue un prix par taille
    public function getSize()
    {
        
    }
}