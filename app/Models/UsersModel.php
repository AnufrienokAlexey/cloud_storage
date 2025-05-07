<?php

namespace app\Models;

use app\Core\Connect;
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
                'SELECT id, username, birthdate FROM cloud_storage.users'
            );
            $stm->execute();
            return $stm->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function get($id): array|null|bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT * FROM cloud_storage.users WHERE id = :id'
            );
            $stm->bindValue(':id', $id);
            $stm->execute();
            return $stm->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function delete($id): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'DELETE FROM cloud_storage.users WHERE id = :id'
            );
            $stm->bindValue(':id', $id);
            if ($stm->execute()) {
                return true;
            }
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function update($id, $username, $email, $password, $birthdate, $role): array|null|bool
    {
        try {
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
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function auth($email, $password): string|null|bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT email FROM cloud_storage.users WHERE email = :email AND password = :password'
            );
            $stm->bindValue(':email', $email);
            $stm->bindValue(':password', $password);
            $stm->execute();
            if ($stm->rowCount() > 0) {
                $user = $stm->fetch();
                return $user['email'];
            }
            return null;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function isAuth(): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT * FROM cloud_storage.users WHERE email = :email'
            );
            $stm->bindValue(':email', $_COOKIE['login']);
            $stm->execute();
            return $stm->rowCount();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function resetPassword($email): string|null|bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT email FROM cloud_storage.users WHERE email = :email'
            );
            $stm->bindValue(':email', $email);
            $stm->execute();
            if ($stm->rowCount() > 0) {
                return $stm->fetch();
            }
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function newPassword($password, $resetKey): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'UPDATE cloud_storage.users
                SET password = :password WHERE (reset_key) = (:reset_key)'
            );
            $stm->bindValue(':reset_key', $resetKey);
            $stm->bindValue(':password', $password);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function addResetKey($resetKey, $email): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'UPDATE cloud_storage.users
                SET reset_key = :reset_key WHERE email = :email'
            );
            $stm->bindValue(':reset_key', $resetKey);
            $stm->bindValue(':email', $email);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function search($email): array|null|bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT id, username, email, birthdate, role FROM cloud_storage.users WHERE email = :email'
            );
            $stm->bindValue(':email', $email);
            $stm->execute();
            return $stm->fetchAll();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }
}
