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
                $email = $_COOKIE['login'];
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
                    $id = self::getIdFullPath($email, $fullPath);
                    if (isset($id['id']) and $id['id'] > 0) {
                        self::updateRow($id['id'], $fullPath);
                    } else {
                        self::addRow($email, $path['path'], $fullPath);
                    }
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

    public static function updateRow($id, $fullPath): void
    {
        $stm = Db::getInstance()->prepare(
            'UPDATE cloud_storage.userpaths 
            SET fullpath = :fullPath 
            WHERE id = :id'
        );
        $stm->bindValue(':id', $id);
        $stm->bindValue(':fullPath', $fullPath);
        $stm->execute();
    }

    public static function deleteRow($id): bool
    {
        if (UserService::isAuth()) {
            $email = $_COOKIE['login'];
            $fullPath = self::getFullPathById($id, $email);
            if (isset($fullPath['fullpath']) &&
                $fullPath['fullpath'] != '' &&
                file_exists(APP . DS . 'Repositories' . DS . $fullPath['fullpath'])) {
                if (unlink(APP . DS . 'Repositories' . DS . $fullPath['fullpath'])) {
                    $stm = Db::getInstance()->prepare(
                        'DELETE FROM cloud_storage.userpaths WHERE id = :id AND email = :email'
                    );
                    $stm->bindValue(':id', $id);
                    $stm->bindValue(':email', $email);
                    return $stm->execute();
                } else {
                    die('Файл удалить не удалось');
                }
            } else {
                die('Не удалось найти файл');
            }
        } else {
            die('Вы не авторизованы');
        }
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

    public static function getIdFullPath($email, $fullPath): array|null|string
    {
        $stm = Db::getInstance()->prepare(
            'SELECT id FROM cloud_storage.userpaths 
                WHERE email = :email AND  fullpath = :fullpath'
        );
        $stm->bindValue(':email', $email);
        $stm->bindValue(':fullpath', $fullPath);
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

    public static function getInfoFile($id): string|null
    {
        if (UserService::isAuth()) {
            $email = $_COOKIE['login'];
            $stm = Db::getInstance()->prepare(
                'SELECT fullpath FROM cloud_storage.userpaths 
                WHERE email = :email AND  id = :id'
            );
            $stm->bindValue(':email', $email);
            $stm->bindValue(':id', $id);
            $stm->execute();
            $id = $stm->fetch();
            if ($id['fullpath']) {
                return $id['fullpath'];
            }
        } else {
            echo('Вы не авторизованы');
        }
        return null;
    }

    private static function getFullPathById($id, $email)
    {
        $stm = Db::getInstance()->prepare(
            'SELECT fullpath FROM cloud_storage.userpaths 
                WHERE email = :email AND  id = :id'
        );
        $stm->bindValue(':id', $id);
        $stm->bindValue(':email', $email);
        $stm->execute();
        return $stm->fetch();
    }

    public static function renameFile(): string|false
    {
        if (UserService::isAuth()) {
            $input = file_get_contents('php://input');
            $request = json_decode($input, true);
            $file = $request['file'];
            $newFile = $request['newFile'];
            if (isset($file) && isset($newFile) && $file != null && $newFile != null) {
                $email = $_COOKIE['login'];
                $path = self::getPath($email);
                $fullPath = $path['path'] . DS . $file;
                $filePath = APP . DS . 'Repositories' . DS . $path['path'] . DS . $file;
                $newFilePath = APP . DS . 'Repositories' . DS . $path['path'] . DS . $newFile;
                if (file_exists($filePath)) {
                    $id = self::getId($email, $fullPath);
                    if (isset($id[0]) && $id[0] != null) {
                        $stm = Db::getInstance()->prepare(
                            'UPDATE cloud_storage.userpaths
                            SET fullpath = :fullpath
                            WHERE id = :id'
                        );
                        $stm->bindValue(':id', $id[0]);
                        $stm->bindValue(':fullpath', $path['path'] . DS . $newFile);
                        if ($stm->execute()) {
                            rename($filePath, $newFilePath);
                            return $newFile;
                        } else {
                            die('Файл не удалось переименовать в бд');
                        }
                    } else {
                        die ('Файл не найден в бд');
                    }
                } else {
                    die ('Файл не найден');
                }
            } else {
                die('Заполните все поля запроса правильно');
            }
        } else {
            echo('Вы не авторизованы');
        }
        return false;
    }
}
