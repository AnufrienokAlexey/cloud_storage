<?php

namespace app\Controllers;

use app\Core\FillDb;

class User
{
    public function list(): void
    {
        echo PHP_EOL . 'userlist()';
//        FillDb::connectToOpenApi();
//        echo('endlist()');
    }

    public function update(): void
    {
        echo 'update()';
    }

    public function get(): void
    {
        echo PHP_EOL . 'userGet()';
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
