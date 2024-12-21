<?php

namespace app\core;

use PDO;

class Connect extends Db
{
    public static function getAllDatabases(): false|array
    {
        $host = CONFIG['host'];
        $username = CONFIG['username'];
        $password = CONFIG['password'];

        try {
            $pdo = new PDO(
                "mysql:host=$host",
                "$username",
                "$password"
            );

            return $pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }

        return false;
    }

    public static function createNewDb(): void
    {
        $host = CONFIG['host'];
        $dbname = CONFIG['dbname'];
        $username = CONFIG['username'];
        $password = CONFIG['password'];

        try {
            $pdo = new PDO(
                "mysql:host=$host",
                "$username",
                "$password"
            );
            $stm = $pdo->prepare("CREATE DATABASE $dbname COLLATE utf8_general_ci");
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
