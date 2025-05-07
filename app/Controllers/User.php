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
        if (isset($_POST['login'], $_POST['password'])) {
            $password = hash('sha256', $_POST['password']);
            $email = UserService::auth($_POST['login'], $password);
            if ($email !== null) {
                setcookie('login', $email, time() + 3600);
                session_start([
                    'cookie_lifetime' => 86400,
                ]);
                $_SESSION['login'] = $email;
                echo("Вы успешно авторизовались как $_POST[login]");
            } else {
                die('Пользователя с таким логином и паролем не существует!');
            }
        }
    }

    public function logout(): void
    {
        setcookie('login', '', time() - 3600);
        if (isset($_SESSION['login'])) {
            session_destroy();
        }
        //header('Location: /');
    }

    public function resetPassword(): void
    {
        if (isset($_POST['email'])) {
            $email = UserService::resetPassword($_POST['email']);
            if ($email !== null) {
                $resetKey = uniqid();
                Connect::createColumn(DB['dbname'], 'users', 'reset_key');
                UserService::addResetKey($resetKey, $email);
                $subject = 'Восстановление пароля';
                $message = 'Вы можете восстановить пароль по следующей ссылке - 
                <a href="http://' . $_SERVER['HTTP_HOST'] . '/new_password/?resetKey=' . $resetKey . '">
                    Сбросить пароль
                </a>';
                $headers = 'From: webmaster@example.com' . "\r\n" .
                    'Reply-To: webmaster@example.com' . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
//                mail($email, $subject, $message, $headers);
                echo $message;
                echo "Ссылка для сброса пароля отправлена на вашу почту!";
            } else {
                echo "Пользователя с таким email - {$_POST['email']} не существует";
            }
        }
    }

    public function newPassword(): void
    {
        if (isset($_GET['resetKey'])) {
            echo 'Вы перешли по персональной ссылке для сброса пароля. Введите новый пароль и подтвердите его';
        } else {
            echo 'Ссылка более не действительна';
//            header('Location: /');
        }
    }

    public function setPassword(): void
    {
        if (isset($_POST['newPassword'], $_POST['newPasswordConfirm']) &&
            $_POST['newPassword'] === $_POST['newPasswordConfirm']) {
            $password = hash('sha256', $_POST['newPassword']);
            if (isset($_GET['resetKey'])) {
                if (UserService::newPassword($password, $_GET['resetKey'])) {
                    unset($_GET['resetKey']);
                    unset($_GET['newPassword']);
                    unset($_GET['newPasswordConfirm']);
                    Connect::deleteColumn('reset_key');
                    echo 'Вы успешно сменили пароль!';
                } else {
                    echo 'Смена пароля не удалась!';
                }
            }
        }
    }

    #[NoReturn] public function search($email = null): void
    {
        Response::send(UserService::search($email), $email);
    }
}
