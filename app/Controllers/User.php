<?php

namespace app\Controllers;

use app\Core\FillDb;

class User
{
    public function list(): void
    {
        echo 'userlist()';
//        FillDb::connectToOpenApi();
//        echo('endlist()');
    }

    public function update(): void
    {
        echo 'update()';
    }

    public function get($id = null): void
    {
        dump($id);
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
