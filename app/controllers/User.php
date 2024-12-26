<?php

namespace app\controllers;

use app\core\FillDb;

class User
{


    public function list(): void
    {
        echo 'list()';
        FillDb::connectToOpenApi();
        echo('endlist()');
    }

    public function update(): void
    {
        echo 'update()';
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
