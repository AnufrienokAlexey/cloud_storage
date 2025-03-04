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

    private static function createTable($dbname, $table): void
    {
        try {
            Db::getInstance()->query("USE $dbname");
            $stm = Db::getInstance()->prepare(
                "CREATE TABLE IF NOT EXISTS $table (
                        id INTEGER AUTO_INCREMENT PRIMARY KEY,
                        username VARCHAR(255))"
            );
            $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    private static function addUsers($dbname, $table): void
    {
        dump(FillDb::connectToOpenApi());
        try {
            foreach (FillDb::connectToOpenApi() as $val) {
                $stm = Db::getInstance()->prepare(
                    "INSERT INTO $dbname.$table
                        (id, username)
                        VALUES(null, :value)"
                );
                $stm->bindParam(':value', $val, PDO::PARAM_STR);
                $stm->execute();
            }
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

        self::createTable($dbname, $table);
        self::addUsers($dbname, $table);
    }


}
