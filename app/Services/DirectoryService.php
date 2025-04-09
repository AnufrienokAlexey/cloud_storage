<?php

namespace app\Services;

use app\Core\Db;
use PDO;

class DirectoryService
{

    public static function addDirectory($directory): string
    {
        if (UserService::isAuth()) {
            $email = $_COOKIE['login'];
            $path = FileService::getPath($email)['path'];
            $fullPath = $path . DS . $directory;
            $newPath = APP . DS . 'Repositories' . DS . $fullPath;
            $id = FileService::getIdFullPath($email, $fullPath);
            if (isset($id['id']) && $id['id'] > 0) {
                return 'Такая директория уже была добавлена ранее';
            }
            if (!file_exists($newPath)) {
                $stm = Db::getInstance()->prepare(
                    'INSERT INTO cloud_storage.userpaths (email, path, fullpath)
                    VALUE (:email, :path, :fullpath)'
                );
                $stm->bindValue(':email', $email);
                $stm->bindValue(':path', $path);
                $stm->bindValue(':fullpath', $fullPath);
                if ($stm->execute()) {
                    mkdir($newPath, 0755, true);
                    if (file_exists($newPath)) {
                        return "Директория $directory успешно добавлена";
                    }
                }
                return 'Не удалось добавить директорию';
            } else {
                return 'Такая директория уже была добавлена ранее';
            }
        }
        return 'Вы не авторизованы';
    }

    public static function getPaths($email): array|null
    {
        $stm = Db::getInstance()->prepare(
            'SELECT path FROM cloud_storage.userpaths WHERE email = :email'
        );
        $stm->bindValue(':email', $email);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function deleteDirectory($id): string
    {
        if (UserService::isAuth()) {
            $email = $_COOKIE['login'];
            $fullPath = FileService::getFullPathById($id, $email);
            if (isset($fullPath['fullpath']) &&
                $fullPath['fullpath'] != '' &&
                file_exists(APP . DS . 'Repositories' . DS . $fullPath['fullpath'])) {
                if (rmdir(APP . DS . 'Repositories' . DS . $fullPath['fullpath'])) {
                    $stm = Db::getInstance()->prepare(
                        'DELETE FROM cloud_storage.userpaths WHERE id = :id AND email = :email'
                    );
                    $stm->bindValue(':id', $id);
                    $stm->bindValue(':email', $email);
                    if ($stm->execute()) {
                        return "Директория $fullPath[fullpath] успешно удалена.";
                    };
                } else {
                    die('Файл удалить не удалось');
                }
            } else {
                die('Не удалось найти директорию');
            }
        } else {
            die('Вы не авторизованы');
        }
    }

    public static function renameDirectory(): string|false
    {
        if (UserService::isAuth()) {
            $input = file_get_contents('php://input');
            $request = json_decode($input, true);
            $file = $request['dir'];
            $newFile = $request['newDir'];
            if (isset($file) && isset($newFile) && $file != null && $newFile != null) {
                $email = $_COOKIE['login'];
                $path = FileService::getPath($email);
                $fullPath = $path['path'] . DS . $file;
                $filePath = APP . DS . 'Repositories' . DS . $path['path'] . DS . $file;
                $newFilePath = APP . DS . 'Repositories' . DS . $path['path'] . DS . $newFile;
                if (file_exists($filePath)) {
                    $id = FileService::getId($email, $fullPath);
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
                    die ('Директория не найдена');
                }
            } else {
                die('Заполните все поля запроса правильно');
            }
        } else {
            echo('Вы не авторизованы');
        }
        return false;
    }

    public static function listFilesById($id): array|false|string
    {
        if (UserService::isAuth()) {
            $email = $_COOKIE['login'];
            $fullPath = FileService::getFullPathById($id, $email);
            if (isset($fullPath['fullpath']) &&
                $fullPath['fullpath'] != '' &&
                file_exists(APP . DS . 'Repositories' . DS . $fullPath['fullpath'])) {
                $dir = (is_dir(APP . DS . 'Repositories' . DS . $fullPath['fullpath']))
                    ? APP . DS . 'Repositories' . DS . $fullPath['fullpath']
                    : dirname(APP . DS . 'Repositories' . DS . $fullPath['fullpath']);
                $result = scandir($dir);
                return array_diff($result, array('.', '..'));
            }
            return "Директория $fullPath[fullpath] не найдена.";
        } else {
            die('Вы не авторизованы');
        }
    }
}