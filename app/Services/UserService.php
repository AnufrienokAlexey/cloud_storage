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

    public static function update($id, $username, $email, $password, $birthdate, $role): array|null
    {
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

    public static function get($id): array|null
    {
        $stm = Db::getInstance()->prepare(
            'SELECT * FROM cloud_storage.users WHERE id = :id'
        );
        $stm->bindValue(':id', $id);
        $stm->execute();
        return $stm->fetchAll();
    }

    public static function loginByEmail($email): array|null
    {
        $stm = Db::getInstance()->prepare('SELECT * FROM cloud_storage.users WHERE email = :email');
        $stm->bindValue(':email', $email);
        $stm->execute();
        if ($stm->rowCount() > 0) {
            $user = $stm->fetch();
            dump($user);
//            if (password_verify($_POST['password'], $user['password'])) {
//                Response::send('<UNK> <UNK> <UNK> <UNK> <UNK>');
//            }
        }
    }
}
