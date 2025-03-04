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

    public static function connect($dbname): void
    {
        $databases = self::getAllDatabases();

        if (!in_array($dbname, $databases)) {
            self::createNewDb($dbname);
        }

        Db::getInstance()->query("USE $dbname");
        $stm = DB::getInstance()->prepare(
            'CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY,
            username VARCHAR(255))'
        );
        $stm->execute();
    }

}
