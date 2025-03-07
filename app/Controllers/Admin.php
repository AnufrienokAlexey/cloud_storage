<?php

namespace app\Controllers;

use app\Core\Response;
use app\Services\AdminService;
use JetBrains\PhpStorm\NoReturn;

class Admin
{
    #[NoReturn] public function list(): void
    {
        Response::send(AdminService::list());
    }

    #[NoReturn] public function get($id = null): void
    {
        Response::send(AdminService::get($id), $id);
    }

    #[NoReturn] public function delete($id = null): void
    {
        Response::send(AdminService::delete($id), $id);
    }

    #[NoReturn] public function update($id, $username, $email, $password, $birthdate, $role): void
    {
        Response::send(AdminService::update($id, $username, $email, $password, $birthdate, $role), $id);
    }
}