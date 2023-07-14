<?php

namespace App\models;

use App\core\Model;

class Users extends Model
{
    public function getUsers()
    {
        return $this->db->get("SELECT * FROM users");
    }
}