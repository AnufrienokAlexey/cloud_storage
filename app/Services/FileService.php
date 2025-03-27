<?php

namespace app\Services;

use app\Core\Db;
use PDO;

class FileService
{
    public static function add($file): void
    {
        if (UserService::isAuth()) {
            if ($_FILES["file"]["error"] == 0) {
                $tmp_name = $_FILES["file"]["tmp_name"];
                $name = basename($_FILES["file"]["name"]);
                if (isset($_COOKIE['login'])) {
                    $email = $_COOKIE['login'];
                } else {
                    die('Вы не авторизованы');
                }
                $path = self::getPath($email);
                $uploadDir = null;
                if ($path == null) {
                    $path = uniqid();
                    $fullPath = $path . '/' . $name;
                    self::addRow($email, $path, $fullPath);
                    $uploadDir = APP . DS . 'Repositories' . DS . $path;
                } else {
                    $path = self::getPath($email);
                    $fullPath = $path['path'] . '/' . $name;
                    $id = self::getId($email, $fullPath);
                    if (count($id) > 0) {
                        self::deleteRow($id[0]);
                    }
                    self::addRow($email, $path['path'], $fullPath);
                    $uploadDir = APP . DS . 'Repositories' . DS . $path['path'];
                }

                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                move_uploaded_file($tmp_name, "$uploadDir" . '/' . $name);
                echo 'Файл ' . $name . ' успешно добавлен';
            } else {
                echo 'Файл содержит ошибки';
            }
        } else {
            echo('Вы не авторизованы');
        }
    }

    public static function deleteRow($id): void
    {
        $stm = Db::getInstance()->prepare(
            'DELETE FROM cloud_storage.userpaths WHERE id = :id'
        );
        $stm->bindValue(':id', $id);
        $stm->execute();
    }

    public static function getId($email, $fullPath): array
    {
        $stm = Db::getInstance()->prepare(
            'SELECT id FROM cloud_storage.userpaths WHERE email = :email AND fullpath = :fullpath'
        );
        $stm->bindValue(':email', $email);
        $stm->bindValue(':fullpath', $fullPath);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_COLUMN);
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

    public static function addRow($email, $path, $fullPath): void
    {
        $stm = Db::getInstance()->prepare(
            "INSERT INTO cloud_storage.userpaths (email, path, fullpath) 
            VALUES (:email,:path, :fullPath)"
        );
        $stm->bindValue(':email', $email);
        $stm->bindValue(':path', $path);
        $stm->bindValue(':fullPath', $fullPath);
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
