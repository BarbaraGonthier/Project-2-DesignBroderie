<?php

namespace App\Model;

class ProductManager extends AbstractManager
{
    public const TABLE = 'product';

    public function __construct()
    {
        parent::__construct(self:: TABLE);
    }


    public function selectOneByIdJoinCategory(int $id)
    {
        $statement = $this->pdo->prepare("SELECT p.*, c.name category_name FROM " . self::TABLE . ' p 
        JOIN category c ON c.id=p.category_id WHERE p.id=:id;');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }





    /**
     * @param int $id
     */

    public function delete(int $id): void
    {


        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
