<?php

namespace App\Model;

class ProductManager extends AbstractManager
{
    public const TABLE = 'product';

    public function __construct()
    {
        parent::__construct(self:: TABLE);
    }
    public function selectAllByCategoryId(int $categoryId)
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . self::TABLE . " WHERE category_id = :categoryId");
        $statement->bindValue(':categoryId', $categoryId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
