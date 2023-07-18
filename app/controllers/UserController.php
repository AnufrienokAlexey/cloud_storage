<?php

namespace App\controllers;

use App\core\View;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController extends Controller
{
    public function list(): void
    {
        $data = [];
        foreach ($this->model->usersList() as $item) {
            $data[] = $item['email'];
        }
        print_r(json_encode($data));
    }

    public function get(): void
    {
        if (!empty($this->id)) {
            if (!empty($this->model->usersGet($this->id))) {
                print_r(json_encode($this->model->usersGet($this->id)));
                return;
            }
            echo 'Пользователя с таким id = ' . $this->id . ' не существует.';
            return;
        }
        echo 'Не хватает параметра id.';
    }

    public function update()
    {
        return;
    }

    public function login()
    {
        session_start();
        $key = 'example_key';
        $payload = [
            'iss' => 'localhost',
            'aud' => 'http://example.com',
            'name' => 'alexey',
            'password' => 'qwerty'
        ];
//        $encode = JWT::encode($payload, $key, 'HS256');
//        echo $encode;
        $header = apache_request_headers();
        if (!empty($header['Authorization'])) {
            try {
                $header = $header['Authorization'];
                $decode = JWT::decode($header, new Key($key, 'HS256'));
                echo 'Ваше имя: ' . $decode->name . '.' . PHP_EOL;
                echo 'Ваш пароль: ' . $decode->password . '.';
            } catch (Exception $e) {
                echo 'Не верный токен';
                View::errorCode(401);
            }
        }
    }

    public function logout()
    {
        session_destroy();
        $this->view->redirect('/');
    }

    public function reset_password()
    {
        echo 'reset_password()';
    }
}
