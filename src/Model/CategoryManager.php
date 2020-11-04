<?php

namespace App\Model;

class CategoryManager extends AbstractManager
{
    public const TABLE = 'categories';

    public function __construct()
    {
        parent::__construct(self:: TABLE);
    }
}
