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
                    if (isset($_POST['dir']) && ($_POST['dir'] != '')) {
                        $fullPath = $path . DS . $_POST['dir'] . DS . $name;
                    }
                    dump($fullPath);
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
                        self::updateRow($id['id'], $fullPath);
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

    public static function getFullPathById($id, $email)
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

    public static function shareId($id): string|null|array
    {
        if (UserService::isAuth()) {
            $stm = Db::getInstance()->prepare(
                'SELECT shared_emails FROM cloud_storage.userpaths
                WHERE id = :id');
            $stm->bindValue(':id', $id);
            if ($stm->execute()) {
                return $stm->fetch();
            }
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
                    $pdo = Db::getInstance()->prepare(
                        'SELECT shared_emails FROM cloud_storage.userpaths
                        WHERE email = :email');
                    $pdo->bindValue(':email', $email);
                    if ($pdo->execute()) {
                        $result = $pdo->fetchAll(PDO::FETCH_COLUMN);
                        if ($user[0]['email'] !== $result[0]) {
                            $stm = Db::getInstance()->prepare(
                                'UPDATE cloud_storage.userpaths 
                                SET shared_emails = :shared_email
                                WHERE email = :email'
                            );
                            $sharedEmail = UserService::get($userId);
                            $stm->bindValue(':email', $user[0]['email']);
                            $stm->bindValue(':shared_email', $sharedEmail[0]['email']);
                            if ($stm->execute()) {
                                return "Пользователю с id = $userId разрешен доступ к вашему файлу с id = $fileId";
                            };
                        }
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

                $stm = Db::getInstance()->prepare(
                    'UPDATE cloud_storage.userpaths
                    SET shared_emails = null
                    WHERE email = :email');
                $stm->bindValue(':email', $email);
                if ($stm->execute()) {
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
