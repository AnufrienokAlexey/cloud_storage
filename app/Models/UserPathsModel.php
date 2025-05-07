<?php

namespace app\Models;

use app\Core\Db;
use PDO;

class UserPathsModel
{

    public static function updateRow($id, $fullPath): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'UPDATE cloud_storage.userpaths 
            SET fullpath = :fullPath 
            WHERE id = :id'
            );
            $stm->bindValue(':id', $id);
            $stm->bindValue(':fullPath', $fullPath);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function deleteRow($id, $email): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'DELETE FROM cloud_storage.userpaths WHERE id = :id AND email = :email'
            );
            $stm->bindValue(':id', $id);
            $stm->bindValue(':email', $email);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function getId($email, $fullPath): array|null|bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT id FROM cloud_storage.userpaths WHERE email = :email AND fullpath = :fullpath'
            );
            $stm->bindValue(':email', $email);
            $stm->bindValue(':fullpath', $fullPath);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function getPath($email): array|null|bool|string
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT path FROM cloud_storage.userpaths WHERE email = :email'
            );
            $stm->bindValue(':email', $email);
            $stm->execute();
            return $stm->fetch();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function getIdFullPath($email, $fullPath): array|null|string
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT id FROM cloud_storage.userpaths 
                    WHERE email = :email AND  fullpath = :fullpath'
            );
            $stm->bindValue(':email', $email);
            $stm->bindValue(':fullpath', $fullPath);
            $stm->execute();
            return $stm->fetch();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function addRow($email, $path, $fullPath): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                "INSERT INTO cloud_storage.userpaths (email, path, fullpath) 
                VALUES (:email,:path, :fullPath)"
            );
            $stm->bindValue(':email', $email);
            $stm->bindValue(':path', $path);
            $stm->bindValue(':fullPath', $fullPath);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function getInfoFile($id, $email): string|null
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT fullpath FROM cloud_storage.userpaths 
                WHERE email = :email AND  id = :id'
            );
            $stm->bindValue(':email', $email);
            $stm->bindValue(':id', $id);
            $stm->execute();
            return $stm->fetch();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function getFullPathById($id, $email): string|null|bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT fullpath FROM cloud_storage.userpaths 
                    WHERE email = :email AND  id = :id'
            );
            $stm->bindValue(':id', $id);
            $stm->bindValue(':email', $email);
            $stm->execute();
            return $stm->fetch();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function renameFile($id, $fullpath): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
            'UPDATE cloud_storage.userpaths
            SET fullpath = :fullpath
            WHERE id = :id'
            );
            $stm->bindValue(':id', $id);
            $stm->bindValue(':fullpath', $fullpath);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function shareId($id): string|null|array|bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT shared_emails FROM cloud_storage.userpaths
            WHERE id = :id'
            );
            $stm->bindValue(':id', $id);
            $stm->execute();
            return $stm->fetch();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function shareIdUserIdPdo($email): array|null|bool
    {
        try {
            $pdo = Db::getInstance()->prepare(
                'SELECT shared_emails FROM cloud_storage.userpaths
                        WHERE email = :email');
            $pdo->bindValue(':email', $email);
            $pdo->execute();
            return $pdo->fetchAll(PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function shareIdUserId($email, $sharedEmail): array|null|bool
    {
        try {
            $stm = Db::getInstance()->prepare(
            'UPDATE cloud_storage.userpaths 
            SET shared_emails = :shared_email
            WHERE email = :email'
            );
            $stm->bindValue(':email', $email);
            $stm->bindValue(':shared_email', $sharedEmail);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function deleteIdUserId($email): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'UPDATE cloud_storage.userpaths
                        SET shared_emails = null
                        WHERE email = :email');
            $stm->bindValue(':email', $email);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function addDirectory($email, $path, $fullPath): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'INSERT INTO cloud_storage.userpaths (email, path, fullpath)
                    VALUE (:email, :path, :fullpath)'
            );
            $stm->bindValue(':email', $email);
            $stm->bindValue(':path', $path);
            $stm->bindValue(':fullpath', $fullPath);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function getPaths($email): bool|null|array|string
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SELECT path FROM cloud_storage.userpaths WHERE email = :email'
            );
            $stm->bindValue(':email', $email);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function deleteDirectory($id, $email): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'DELETE FROM cloud_storage.userpaths WHERE id = :id AND email = :email'
            );
            $stm->bindValue(':id', $id);
            $stm->bindValue(':email', $email);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

    public static function renameDirectory($id, $path): bool
    {
        try {
            $stm = Db::getInstance()->prepare(
                'UPDATE cloud_storage.userpaths
                            SET fullpath = :fullpath
                            WHERE id = :id'
            );
            $stm->bindValue(':id', $id);
            $stm->bindValue(':fullpath', $path);
            return $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }
}
