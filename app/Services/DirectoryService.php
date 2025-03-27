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
                        return 'Директория успешно добавлена';
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
}