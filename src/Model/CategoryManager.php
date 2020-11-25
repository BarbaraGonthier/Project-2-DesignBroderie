<?php

namespace App\Model;

use PDO;

class CategoryManager extends AbstractManager
{
    public const TABLE = 'category';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
    public function selectAllLimit()
    {
        return $this->pdo->query('SELECT * FROM ' . self::TABLE . " LIMIT 6")->fetchAll();
    }
    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare("DELETE FROM " . self::TABLE . " WHERE id=:id");
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();
    }
    public function insert(array $category)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name`, image) VALUES (:name, :image)");
        $statement->bindValue('name', $category['name'], PDO::PARAM_STR);
        $statement->bindValue('image', $category['image'], PDO::PARAM_STR);

        $statement->execute();
    }
}
