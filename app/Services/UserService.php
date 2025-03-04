<?php

namespace app\Services;

use app\Core\Db;

class UserService
{
    public static function list(): array|null
    {
        $stm = Db::getInstance()->prepare('SELECT * FROM users');
        $stm->execute();
        return $stm->fetchAll();
    }
}