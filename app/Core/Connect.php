<?php

namespace app\Core;

use PDO;

class Connect extends Db
{
    private static function getAllDatabases(): false|array
    {
        return Db::getInstance()
            ->query('SHOW DATABASES')
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    private static function createNewDb($dbname): void
    {
        try {
            $stm = Db::getInstance()->prepare(
                "CREATE DATABASE $dbname COLLATE utf8_general_ci;"
            );
            $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    private static function createTable($table): void
    {
        try {
            $stm = Db::getInstance()->prepare(
                'CREATE TABLE IF NOT EXISTS users (
                        id INTEGER AUTO_INCREMENT PRIMARY KEY,
                        username VARCHAR(255))'
            );
            $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    private static function addUsers($table): void
    {
        try {
            $stm = Db::getInstance()->prepare(
                "INSERT INTO cloud_storage.users
                        (id, username)
                        VALUES(null, 'padded')"
            );
            $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public static function connect($dbname, $table): void
    {
        $databases = self::getAllDatabases();

        if (!in_array($dbname, $databases)) {
            self::createNewDb($dbname);
        }

        Db::getInstance()->query("USE $dbname");
        self::createTable($table);
        self::addUsers($table);
    }


}
