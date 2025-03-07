<?php

namespace app\Services;

use app\Core\Db;

class UserService
{
    public static function list(): array|null
    {
        $stm = Db::getInstance()->prepare(
            'SELECT id, username, birthdate FROM cloud_storage.users'
        );
        $stm->execute();
        return $stm->fetchAll();
    }

    public static function update(): array|null
    {
        $stm = Db::getInstance()->prepare(
            'SELECT * FROM cloud_storage.users'
        );
        $stm->execute();
        return $stm->fetchAll();
    }

    public static function get($id): array|null
    {
        $stm = Db::getInstance()->prepare(
            'SELECT * FROM cloud_storage.users WHERE id = :id'
        );
        $stm->bindValue(':id', $id);
        $stm->execute();
        return $stm->fetchAll();
    }
}