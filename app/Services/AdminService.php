<?php

namespace app\Services;

use app\Core\Db;
use app\Models\UsersModel;

class AdminService
{
    public static function isAdmin(): bool
    {
        if (isset ($_COOKIE['login'])) {
            if (UsersModel::isAdmin($_COOKIE['login'], 'admin') == 1) {
                return true;
            }
        }
        return false;
    }

    public static function list(): array|null|bool
    {
        return UsersModel::list();
    }

    public static function get($id): array|null|bool
    {
        return UsersModel::get($id);
    }

    public static function delete($id): bool
    {
        if(UsersModel::delete($id)) {
            return "Пользователь с id = $id удален";
        }
        return "Не получилось удалить пользователя с id = $id";
    }

    public static function update($id): array|null|bool
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

        return UsersModel::update($id, $username, $email, $password, $birthdate, $role);
    }
}
