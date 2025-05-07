<?php

namespace app\Controllers;

use app\Core\Response;
use app\Services\AdminService;
use JetBrains\PhpStorm\NoReturn;

class Admin
{
    #[NoReturn] public function list(): void
    {
        if (AdminService::isAdmin()) {
            Response::send(AdminService::list());
        } else {
            echo('Вы не можете просматривать список пользователей так как Вы не авторизовались как администратор');
        }
    }

    #[NoReturn] public static function get($id = null): void
    {
        if (AdminService::isAdmin()) {
            Response::send(AdminService::get($id), $id);
        } else {
            echo('Вы не можете просматривать пользователя так как Вы не авторизовались как администратор');
        }
    }

    #[NoReturn] public function delete($id = null): void
    {
        if (AdminService::isAdmin()) {
            Response::send(AdminService::delete($id), $id);
        } else {
            echo('Вы не можете удалить пользователя, так как Вы не авторизовались как администратор');
        }
    }

    #[NoReturn] public function update($id = null): void
    {
        if (AdminService::isAdmin()) {
            Response::send(AdminService::update($id), $id);
        } else {
            echo('Вы не можете обновить данные список пользователя так как Вы не авторизовались как администратор');
        }
    }
}