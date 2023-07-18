<?php

namespace App\models;

use App\core\Model;

class Users extends Model
{
    public function usersList(): array
    {
        return $this->db->get('SELECT * FROM users');
    }

    public function usersGet($id): array
    {
        return $this->db->getById("SELECT * FROM `users` WHERE `id` = $id");
    }
}
