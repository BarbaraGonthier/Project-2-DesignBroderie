<?php

namespace App\Model;

class PatternManager extends AbstractManager
{
    public const TABLE = "pattern";

    public function __construct()
    {
        parent::__construct(self:: TABLE);
    }
    public function insert(array $pattern)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`name`, photo) VALUES (:name, :photo)");
        $statement->bindValue('name', $pattern['name'], \PDO::PARAM_STR);
        $statement->bindValue('photo', $pattern['photo'], \PDO::PARAM_STR);

        $statement->execute();
    }
}
