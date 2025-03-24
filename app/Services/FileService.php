<?php

namespace app\Services;

class FileService
{
    public static function add($file): void
    {
        if (UserService::isAuth()) {
            dump($_COOKIE['login']);
            dump($file);
            //$email = $_COOKIE['login'];
            //$email = 'gerhard.vonrueden@gmail.com';
            $email = 'login';
            $uploadDir = APP . DS . 'Repositories' . DS . $email;

            dump($uploadDir);
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if ($_FILES["file"]["error"] == 0) {
                $tmp_name = $_FILES["file"]["tmp_name"];
                $name = basename($_FILES["file"]["name"]);
                move_uploaded_file($tmp_name, "$uploadDir" . '/' . $name);
            }
        }
    }
}