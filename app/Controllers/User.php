<?php

namespace app\Controllers;

use app\Core\FillDb;
use app\Core\Response;

class User
{
    public function list(): void
    {
        $response = new Response('Hello World', 202);
        dump($response->send());
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
