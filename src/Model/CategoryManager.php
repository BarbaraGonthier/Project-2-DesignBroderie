<?php

namespace App\Model;

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
    public function insert(array $category)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name`, image) VALUES (:name, :image)");
        $statement->bindValue('name', $category['name'], \PDO::PARAM_STR);
        $statement->bindValue('image', $category['image'], \PDO::PARAM_STR);

        $statement->execute();
    }
}
