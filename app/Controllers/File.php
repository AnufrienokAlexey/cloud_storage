<?php

namespace app\Controllers;

use app\Core\Response;
use JetBrains\PhpStorm\NoReturn;

class File
{
    #[NoReturn] public function list(): void
    {
        Response::send('filesList');
    }

    #[NoReturn] public function getId($id = null): void
    {
        Response::send('filesGetId', $id);
    }

    #[NoReturn] public function add(): void
    {
        Response::send('filesAdd');
    }

    #[NoReturn] public function rename(): void
    {
        Response::send('filesRename');
    }

    #[NoReturn] public function removeId($id = null): void
    {
        Response::send('filesRemoveId', $id);
    }

    #[NoReturn] public function shareId($id = null): void
    {
        Response::send('filesShareId', $id);
    }

    #[NoReturn] public function shareIdUserId($id = null, $userId = null): void
    {
        Response::send('filesShareIdUserId', $id, $userId);
    }

    #[NoReturn] public function deleteIdUserId($id = null, $userId = null): void
    {
        Response::send('filesDeleteIdUserId', $id, $userId);
    }

}