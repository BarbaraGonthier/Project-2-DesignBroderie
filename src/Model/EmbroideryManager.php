<?php

namespace App\Model;

class EmbroideryManager extends AbstractManager
{
    public const TABLE = "embroidery";

    public function __construct()
    {
        parent::__construct(self:: TABLE);
    }
}
