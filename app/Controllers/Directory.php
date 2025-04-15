<?php

namespace app\Controllers;

use app\Core\Response;
use app\Services\DirectoryService;
use JetBrains\PhpStorm\NoReturn;

class Directory
{
    #[NoReturn] public function add(): void
    {
        if (!empty($_POST['directory'])) {
            Response::send(DirectoryService::addDirectory($_POST['directory']));
        } else {
            Response::send('В теле запроса нет имени директории');
        }
//        Response::send(DirectoryService::addDirectory());
    }

    #[NoReturn] public function rename(): void
    {
        Response::send(DirectoryService::renameDirectory());
    }

    #[NoReturn] public function getId($id = null): void
    {
        Response::send(DirectoryService::listFilesById($id), $id);
    }

    #[NoReturn] public function deleteId($id = null): void
    {
        Response::send(DirectoryService::deleteDirectory($id));
//        Response::send('directoriesDeleteId', $id);
    }
}
