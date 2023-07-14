<?php

namespace App\core;

abstract class Model
{
    public Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }
}