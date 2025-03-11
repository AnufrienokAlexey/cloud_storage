<?php

namespace app\Controllers;

use app\Core\Connect;
use app\Core\Db;
use app\Core\Request;
use app\Core\Response;
use app\Services\UserService;
use JetBrains\PhpStorm\NoReturn;

class User
{
    #[NoReturn] public function list(): void
    {
        Response::send(UserService::list());
    }

    #[NoReturn] public function update(): void
    {
        $arr = [];
        $i = 0;
        $columns = Connect::getColumnsTable(DB['dbname'], 'users');
        $entityBody = Request::getEntityBody();
        foreach ($entityBody as $key => $value) {
            if (in_array($key, $columns)) {
                $arr[$key] = $value;
                $i++;
            }
        }
        if ($i === count($columns)) {
            $id = $arr['id'];
            $username = $arr['username'];
            $email = $arr['email'];
            $password = hash('sha256', $arr['password']);
            $birthdate = $arr['birthdate'];
            $role = $arr['role'];
            Response::send(UserService::update($id, $username, $email, $password, $birthdate, $role), $id);
        } else {
            Response::send('Во входящем теле запроса отсутсвуют все данные для изменения');
        }
    }

    #[NoReturn] public function get($id = null): void
    {
        Response::send(UserService::get($id), $id);
    }

    #[NoReturn] public function login(): void
    {
        Response::send(UserService::loginByEmail($_POST['email']));
    }

    public function logout()
    {
    }

    public function resetPassword()
    {
    }
}
