<?php

namespace app\Controllers;

use app\Core\Connect;
use app\Core\Registry;
use app\Core\Response;
use app\Services\UserService;
use JetBrains\PhpStorm\NoReturn;

class User
{
    #[NoReturn] public function list(): void
    {
        Connect::connect(DB['dbname'], 'users');
        dump(UserService::list());
        //Response::send('users list');
    }

    #[NoReturn] public function update(): void
    {
        Response::send('userUpdate');
    }

    #[NoReturn] public function get(): void
    {
        Response::send('userGet');
    }

    public function login()
    {
    }

    public function logout()
    {
    }

    public function reset_password()
    {
    }
}
