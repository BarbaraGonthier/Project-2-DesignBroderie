<?php

namespace App\Model;

class LogoManager extends AbstractManager
{
    public const TABLE = "logo";

    public function __construct()
    {
        parent::__construct(self:: TABLE);
    }
}
