<?php

namespace app\Controllers;

use app\Core\Connect;
use app\Core\Request;
use app\Core\Response;
use app\Core\Router;
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
        if (isset($_POST['login'], $_POST['password'])) {
            $password = hash('sha256', $_POST['password']);
            $email = UserService::auth($_POST['login'], $password);
            if ($email !== null) {
                setcookie('login', $email, time() + 3600);
            } else {
                die('Пользователя с таким логином и паролем не существует!');
            }
        }
    }

    public function logout(): void
    {
        setcookie('login', '', time() - 3600);
        header('Location: /');
    }

    public function isEmailExist(): void
    {
        Connect::createColumn('cloud_storage', 'users', 'reset_key');
        if (isset($_POST['email'])) {
            $email = UserService::isEmailExist($_POST['email']);
            if ($email !== null) {
                $_GET['email'] = $email;
                header('Location: /reset_password');
//                if (isset($_POST['reset-password'])) {
//                    if (UserService::resetPassword($_POST['reset-password'], $email)) {
//                        echo 'Вы успешно сменили пароль!';
//                    } else {
//                        echo 'Смена пароля не удалась!';
//                    }
//                }
            } else {
                echo "Пользователя с таким email - {$_POST['email']} не существует";
            }
        }
    }

    public function newPassword(): void
    {
        dump($_GET['email']);
        $resetKey = uniqid();
        UserService::addResetKey($resetKey, $_GET['email']);
        $subject = 'Восстановление пароля';
        $message = 'Вы можете восстановить пароль по следующей ссылке - 
        <a href=http://' . $_SERVER['HTTP_HOST'] . '/reset_password/?resetKey=' . $resetKey . '>Сбросить пароль</a>';
        $headers = 'From: webmaster@example.com' . "\r\n" .
            'Reply-To: webmaster@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        echo $message;
    }
}
