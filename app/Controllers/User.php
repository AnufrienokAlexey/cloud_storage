<?php

namespace app\Controllers;

use app\Core\FillDb;
use app\Core\Response;
use JetBrains\PhpStorm\NoReturn;

class User
{
    #[NoReturn] public function list(): void
    {
        $response = new Response('UserList', 202);
        $response->send();
    }

    public function update(): void
    {
        echo 'update()';
    }

    public function get(): void
    {
        echo 'userGet()';
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
