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

    public function selectAllByCategoryId(int $categoryId)
    {
        $statement = $this->pdo->prepare("SELECT p.*, c.name AS category_name FROM " . self::TABLE .
            " AS p JOIN category AS c ON p.category_id = c.id WHERE p.category_id = :categoryId");
        $statement->bindValue(':categoryId', $categoryId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
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
