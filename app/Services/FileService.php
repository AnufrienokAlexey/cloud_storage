<?php

namespace app\Services;

use app\Core\Db;
use app\Models\UserPathsModel;
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
                    if (isset($_POST['dir']) && ($_POST['dir'] != '')) {
                        $fullPath = $path . DS . $_POST['dir'] . DS . $name;
                    }
                    self::addRow($email, $path, $fullPath);
                    $uploadDir = APP . DS . 'Repositories' . DS . $path;

                    if (isset($_POST['dir']) && ($_POST['dir'] != '')) {
                        $uploadDir = APP . DS . 'Repositories' . DS . $path . DS . $_POST['dir'];
                    }
                } else {
                    $path = self::getPath($email);
                    $fullPath = $path['path'] . DS . $name;

                    if (isset($_POST['dir']) && ($_POST['dir'] != '')) {
                        $fullPath = $path['path'] . DS . $_POST['dir'] . DS . $name;
                    }

                    $id = self::getIdFullPath($email, $fullPath);
                    if (isset($id['id']) and $id['id'] > 0) {
                        if(self::updateRow($id['id'], $fullPath)) {
                            echo 'Данные успешно обновлены';
                        }
                    } else {
                        self::addRow($email, $path['path'], $fullPath);
                    }

                    if (isset($_POST['dir']) && ($_POST['dir'] != '')) {
                        $uploadDir = APP . DS . 'Repositories' . DS . $path['path'] . DS . $_POST['dir'];
                    } else {
                        $uploadDir = APP . DS . 'Repositories' . DS . $path['path'];
                    }
                }

                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                move_uploaded_file($tmp_name, "$uploadDir" . DS . $name);
                echo 'Файл ' . $name . ' успешно добавлен';
            } else {
                echo 'Файл содержит ошибки';
            }
        } else {
            echo('Вы не авторизованы');
        }
    }

    public static function updateRow($id, $fullPath): bool
    {
        if (UserPathsModel::updateRow($id, $fullPath)) {
            return true;
        }
        return false;
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
                    if (UserPathsModel::deleteRow($id, $email)) {
                        echo 'Данные успешно удалены из бд';
                        return true;
                    }
                } else {
                    die('Файл удалить не удалось');
                }
            } else {
                die('Не удалось найти файл');
            }
        } else {
            die('Вы не авторизованы');
        }
        return false;
    }

    public static function getId($email, $fullPath): array|null|bool
    {
        return UserPathsModel::getId($email, $fullPath);
    }

    public static function getPath($email): array|null|string
    {
        return UserPathsModel::getPath($email);
    }

    public static function getIdFullPath($email, $fullPath): array|null|string
    {
        return UserPathsModel::getIdFullPath($email, $fullPath);
    }

    public static function addRow($email, $path, $fullPath): void
    {
        if (UserPathsModel::addRow($email, $path, $fullPath)) {
            echo "Данные успешно добавлены";
        } else {
            echo "Не удалось добавить данные";
        }
    }

    public static function list(): array|null
    {
        if (UserService::isAuth()) {
            $email = $_COOKIE['login'];
            $path = self::getPath($email);
            dump($path);
            if ($path != null) {
                $uploadDir = APP . DS . 'Repositories' . DS . $path['path'];
            } else {
                die ("Нет записи о файле в бд");
            }

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
            $id = UserPathsModel::getInfoFile($id, $email);
            if (isset($id['fullpath'])) {
                return $id['fullpath'];
            }
        } else {
            echo('Вы не авторизованы');
        }
        return null;
    }

    public static function getFullPathById($id, $email): bool|string|null
    {
        return UserPathsModel::getFullPathById($id, $email);
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
                        if (UserPathsModel::renameFile($id[0], $path['path'] . DS . $newFile)) {
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

    public static function shareId($id): string|null|array
    {
        if (UserService::isAuth()) {
            return UserPathsModel::shareId($id);
        }
        return 'Вы не авторизованы';
    }

    public static function shareIdUserId(mixed $id, mixed $userId)
    {
        if (UserService::isAuth()) {
            $input = file_get_contents('php://input');
            $request = json_decode($input, true);

            if (isset($request['id']) && isset($request['user_id']) && $request['id'] != null && $request['user_id'] != null) {
                $fileId = $request['id'];
                $userId = $request['user_id'];
                $email = $_COOKIE['login'];
                $path = self::getPath($email);
                $fullPath = self::getFullPathById($fileId, $email);
                $filePath = APP . DS . 'Repositories' . DS . $path['path'] . DS . $fullPath;
                if (file_exists($filePath)) {
                    $user = UserService::search($email);
                        $result = UserPathsModel::shareIdUserIdPdo($email);
                        if ($user[0]['email'] !== $result[0]) {
                            $sharedEmail = UserService::get($userId);
                            if (UserPathsModel::shareIdUserId($user[0]['email'], $sharedEmail[0]['email'])) {
                                return "Пользователю с id = $userId разрешен доступ к вашему файлу с id = $fileId";
                            };
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

    public static function deleteIdUserId(mixed $id, mixed $userId)
    {
        if (UserService::isAuth()) {
            $input = file_get_contents('php://input');
            $request = json_decode($input, true);

            if (isset($request['id']) && isset($request['user_id']) && $request['id'] != null && $request['user_id'] != null) {
                $fileId = $request['id'];
                $userId = $request['user_id'];
                $email = $_COOKIE['login'];
                if (UserPathsModel::deleteIdUserId($email)) {
                    return "Пользователю с id = $userId удален доступ к вашему файлу с id = $fileId";
                };
            } else {
                die('Заполните все поля запроса правильно');
            }
        } else {
            echo('Вы не авторизованы');
        }
        return false;
    }
}
