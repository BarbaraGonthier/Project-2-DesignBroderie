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

    public function selectWithCategory()
    {
        return $this->pdo->query(
            'SELECT p.*, c.name as category ' .
            'FROM ' . self::TABLE . ' p ' .
            'LEFT JOIN category c ON p.category_id=c.id'
        )->fetchAll();
    }


    public function insert(array $product): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`category_id` , `name` , `gender` , 
            `reference` , `image` , `description` , `price`) 
            VALUES (:category_id , :name , :gender , :reference, :image ,:description, :price)");
        $statement->bindValue('category_id', $product['category'], \PDO::PARAM_INT);
        $statement->bindValue('name', $product['name'], \PDO::PARAM_STR);
        $statement->bindValue('gender', $product['gender'], \PDO::PARAM_STR);
        $statement->bindValue('reference', $product['reference'], \PDO::PARAM_INT);
        $statement->bindValue('image', $product['image'], \PDO::PARAM_STR);
        $statement->bindValue('description', $product['description'], \PDO::PARAM_STR);
        $statement->bindValue('price', $product['price']);


        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }
}
