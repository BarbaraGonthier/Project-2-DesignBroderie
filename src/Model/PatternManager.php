<?php

namespace App\Model;

class PatternManager extends AbstractManager
{
    public const TABLE = "pattern";

    public function __construct()
    {
        parent::__construct(self:: TABLE);
    }
}
