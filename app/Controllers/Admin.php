<?php

namespace app\Controllers;

use app\Core\Response;
use JetBrains\PhpStorm\NoReturn;

class Admin
{
    #[NoReturn] public function list(): void
    {
        Response::send('adminList');
    }

    #[NoReturn] public function get($id = null): void
    {
        Response::send('adminGet');
    }

    #[NoReturn] public function delete($id = null): void
    {
        Response::send('adminDelete', $id);
    }

    #[NoReturn] public function update($id = null): void
    {
        Response::send('adminUpdate', $id);
    }
}