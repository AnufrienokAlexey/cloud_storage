<?php

namespace app\Services;

use app\Core\Db;

class AdminService
{
    public static function isAdmin(): bool
    {
        if (isset ($_COOKIE['login'])) {
            $stm = Db::getInstance()->prepare(
                'SELECT * FROM cloud_storage.users WHERE email = :email AND role = :role'
            );
            $stm->bindValue(':email', $_COOKIE['login']);
            $stm->bindValue(':role', 'admin');
            $stm->execute();
            if ($stm->rowCount() == 1) {
                return true;
            }
        }
        return false;
    }

    public static function list(): array|null
    {
        $stm = Db::getInstance()->prepare(
            'SELECT id, username FROM cloud_storage.users'
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

    public static function delete($id): array|null
    {
        $stm = Db::getInstance()->prepare(
            'DELETE FROM cloud_storage.users WHERE id = :id'
        );
        $stm->bindValue(':id', $id);
        $stm->execute();
        return $stm->fetchAll();
    }

    public static function update($id): array|null
    {
        $input = file_get_contents('php://input');
        $request = json_decode($input, true);
        $username = $request['username'] ?? null;
        $email = $request['email'] ?? null;
        if (isset($request['password'])) {
            $password = hash('sha256', $request['password']);
        } else {
            $password = null;
        }
        $birthdate = $request['birthdate'] ?? null;
        $role = $request['role'] ?? null;

        $stm = Db::getInstance()->prepare(
            'UPDATE cloud_storage.users
            SET username = :username, email = :email, password = :password, birthdate = :birthdate, role = :role
            WHERE id = :id'
        );
        $stm->bindValue(':id', $id);
        $stm->bindValue(':username', $username);
        $stm->bindValue(':email', $email);
        $stm->bindValue(':password', $password);
        $stm->bindValue(':birthdate', $birthdate);
        $stm->bindValue(':role', $role);
        $stm->execute();
        return $stm->fetchAll();
    }

}