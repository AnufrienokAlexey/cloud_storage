<?php

namespace app\Controllers;

use app\Core\Response;
use app\Services\FileService;
use JetBrains\PhpStorm\NoReturn;

class File
{
    #[NoReturn] public function list(): void
    {
        Response::send(FileService::list());
    }

    #[NoReturn] public function getId($id = null): void
    {
        Response::send(FileService::getInfoFile($id), $id);
    }

    #[NoReturn] public function add(): void
    {
        if (isset($_FILES['file'])) {
            FileService::add($_FILES['file']);
        }
    }

    #[NoReturn] public function rename(): void
    {
        Response::send(FileService::renameFile());
    }

    #[NoReturn] public function removeId($id = null): void
    {
        Response::send(FileService::deleteRow($id), $id);
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