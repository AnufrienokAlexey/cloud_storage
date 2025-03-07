<?php

namespace app\Controllers;

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
        Response::send(UserService::update());
    }

    #[NoReturn] public function get($id = null): void
    {
        Response::send(UserService::get($id), $id);
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
