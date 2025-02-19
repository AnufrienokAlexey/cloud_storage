<?php

namespace app\Controllers;

use app\Core\FillDb;

class User
{
    public function list(): void
    {
        echo 'userList()';
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
