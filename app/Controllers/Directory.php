<?php

namespace app\Controllers;

use app\Core\Response;
use JetBrains\PhpStorm\NoReturn;

class Directory
{
    #[NoReturn] public function add(): void
    {
        Response::send('directoriesAdd');
    }

    #[NoReturn] public function rename(): void
    {
        Response::send('directoriesRename');
    }

    #[NoReturn] public function getId($id = null): void
    {
        Response::send('directoriesGetId', $id);
    }

    #[NoReturn] public function deleteId($id = null): void
    {
        Response::send('directoriesDeleteId', $id);
    }
}