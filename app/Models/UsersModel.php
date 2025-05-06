<?php

namespace app\Models;

use app\Core\Db;

class UsersModel
{
    public static function isAdmin($email, $role): array|null|bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT * FROM cloud_storage.users WHERE email = :email AND role = :role'
            );
            $stm->bindValue(':email', $email);
            $stm->bindValue(':role', $role);
            $stm->execute();
            return $stm->rowCount();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function list(): array|null|bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT id, username FROM cloud_storage.users'
            );
            $stm->execute();
            return $stm->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }
}
