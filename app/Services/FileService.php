<?php

namespace app\Services;

use app\Core\Db;
use PDO;

class FileService
{
    public static function add($file): void
    {
        if (UserService::isAuth()) {
            $email = $_COOKIE['login'];
            $path = self::getPath($email);
            if ($path == null) {
                self::addRow($email, uniqid());
            }
            $path = self::getPath($email);
            $uploadDir = APP . DS . 'Repositories' . DS . $path['path'];

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if ($_FILES["file"]["error"] == 0) {
                $tmp_name = $_FILES["file"]["tmp_name"];
                $name = basename($_FILES["file"]["name"]);
                move_uploaded_file($tmp_name, "$uploadDir" . '/' . $name);
            }
        } else {
            echo('Вы не авторизованы');
        }
    }

    public static function getPath($email): array|null|string
    {
        $stm = Db::getInstance()->prepare(
            'SELECT path FROM cloud_storage.userpaths WHERE email = :email'
        );
        $stm->bindValue(':email', $email);
        $stm->execute();
        return $stm->fetch();
    }

    public static function addRow($email, $path): void
    {
        $stm = Db::getInstance()->prepare(
            "INSERT INTO cloud_storage.userpaths (email, path) VALUES (:email,:path)"
        );
        $stm->bindValue(':email', $email);
        $stm->bindValue(':path', $path);
        $stm->execute();
    }

    public static function list(): array|null
    {
        if (UserService::isAuth()) {
            $email = $_COOKIE['login'];
            $path = self::getPath($email);
            $uploadDir = APP . DS . 'Repositories' . DS . $path['path'];
            return array_diff(scandir($uploadDir), array('..', '.'));
        } else {
            echo('Вы не авторизованы');
        }
        return null;
    }
}
