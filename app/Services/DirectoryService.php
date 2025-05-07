<?php

namespace app\Services;

use app\Core\Db;
use app\Models\UserPathsModel;
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
                if (UserPathsModel::addDirectory($email, $path, $fullPath)) {
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

    public static function getPaths($email): bool|null|array|string
    {
        return UserPathsModel::getPath($email);
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
                    if (UserPathsModel::deleteDirectory($id, $email)) {
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
                        if (UserPathsModel::renameDirectory($id[0], $path['path'] . DS . $newFile)) {
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