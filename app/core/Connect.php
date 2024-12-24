<?php

namespace app\core;

use PDO;

class Connect extends Db
{
    public static function db(): object
    {
        return new Db(
            CONFIG['host'],
            CONFIG['dbname'],
            CONFIG['charset'],
            CONFIG['username'],
            CONFIG['password'],
        );
    }

    public static function getAllDatabases(): false|array
    {
        $host = self::db()->host;
        $username = self::db()->username;
        $password = self::db()->password;

        try {
            $pdo = new PDO(
                "mysql:host=$host",
                "$username",
                "$password"
            );

            return $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            die('MySQL is not connected!');
        }
    }

    public static function createNewDb(): void
    {
        $host = self::db()->host;
        $dbname = self::db()->dbname;
        $username = self::db()->username;
        $password = self::db()->password;

        try {
            $pdo = new PDO(
                "mysql:host=$host",
                "$username",
                "$password"
            );
            $stm = $pdo->prepare(
                "CREATE DATABASE $dbname COLLATE utf8_general_ci"
            );
            $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public static function connect(): void
    {
        $databases = self::getAllDatabases();

        if (!in_array('cloud_storage', $databases)) {
            self::createNewDb('cloud_storage');
        }
    }
}
