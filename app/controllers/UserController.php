<?php

namespace App\controllers;

use App\core\View;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController extends Controller
{
    public function list()
    {
//        Блок кода, если бы у нас frontend был бы свой
        $this->view->render('Страница обо мне', $this->model->usersList());

//        Блок кода для API.
//        function map($array): array {
//            $a = [];
//            foreach ($array as $item) {
//                $a[] = $item['email'];
//            }
//            return $a;
//        }
//        $arr = $this->model->usersList();
//        print_r(map($arr));
    }

    public function get()
    {
//        Блок кода, если бы у нас frontend был бы свой
        if (isset($_GET['id'])) {
            $this->view->render('Страница обо мне', $this->model->usersGet($_GET['id']));
            return;
        }
        $this->view->render('Страница обо мне', $this->model->usersList());

//        Блок кода для API. На мой взгляд в REST API не принято использовать в ответе поясняющие фразы, а только чистые данные. Но я решил указать:
//        if (!empty($_GET['id'])) {
//            if (!empty($this->model->usersGet($_GET['id']))) {
//                echo 'Найден пользователь с id = '.$_GET['id'].'. Его email = '.$this->model->usersGet($_GET['id'])[0]['email'].'.';
//                return;
//            }
//            echo 'Пользователя с таким id = '.$_GET['id'].' не существует.';
//            return;
//        }
//        echo 'Не хватает параметра id.';
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