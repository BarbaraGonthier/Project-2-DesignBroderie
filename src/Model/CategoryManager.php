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
    public function selectAllLimit()
    {
        return $this->pdo->query('SELECT * FROM ' . self::TABLE . " LIMIT 6")->fetchAll();
    }
}
