<?php

namespace app\Services;

use app\Core\Connect;
use app\Core\Db;
use app\Models\UsersModel;

class UserService
{
    public static function list(): array|null|bool
    {
        return UsersModel::list();
    }

    public static function update($id, $username, $email, $password, $birthdate, $role): array|null|bool
    {
        return UsersModel::update($id, $username, $email, $password, $birthdate, $role);
    }

    public static function get($id): array|null|bool
    {
        return UsersModel::get($id);
    }

    public static function auth($email, $password): string|null|bool
    {
        return UsersModel::auth($email, $password);
    }

    public static function isAuth(): bool
    {
        if (isset ($_COOKIE['login']) && (UsersModel::isAuth() == 1)) {
            return true;
        }
        return false;
    }

    public static function resetPassword($email): string|null|bool
    {
        $user = UsersModel::resetPassword($email);
        if (isset($user['email'])) {
            return $user['email'];
        }
        return null;
    }

    public static function newPassword($password, $resetKey): bool
    {
        if (Connect::getColumn('users', 'reset_key')) {
            return UsersModel::newPassword($password, $resetKey);
        } else {
            echo 'Ссылка более не действительна! Возможно Вы уже изменили пароль ранее.' . PHP_EOL;
        }
        return false;
    }

    public static function addResetKey($resetKey, $email): bool
    {
        return UsersModel::addResetKey($resetKey, $email);
    }

    public static function search($email): array|null|bool
    {
        return UsersModel::search($email);
    }
}
