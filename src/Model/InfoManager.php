<?php

namespace App\Model;

class InfoManager extends AbstractManager
{
    public const TABLE = "info";

    public function __construct()
    {
        parent::__construct(self:: TABLE);
    }

    public function infoSave(array $info): void
    {
        $query = "INSERT INTO " . self::TABLE .
            " (firstname, lastname, email, message)
        VALUES
        (:firstname, :lastname, :email, :message);";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':firstname', $info['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $info['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $info['email'], \PDO::PARAM_STR);
        $statement->bindValue(':message', $info['message'], \PDO::PARAM_STR);


        $statement->execute();
    }
}
