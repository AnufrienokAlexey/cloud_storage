<?php

namespace app\Controllers;

use app\Core\Db;
use app\Core\Registry;
use app\Core\Response;
use JetBrains\PhpStorm\NoReturn;

class User
{
    #[NoReturn] public function list(): void
    {
        Response::send('list');
    }

    #[NoReturn] public function update(): void
    {
        Response::send('update');
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
