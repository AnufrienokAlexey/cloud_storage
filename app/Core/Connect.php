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
                        username VARCHAR(255),
                        email VARCHAR(255),
                        password VARCHAR(255),
                        birthdate  VARCHAR(255),
                        role VARCHAR(100))"
            );
            $stm->execute();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    private static function addUsers($dbname, $table): void
    {
        $users = FillDb::connectToOpenApi();
        try {
            foreach ($users as $user) {
                $stm = Db::getInstance()->prepare(
                    "INSERT INTO $dbname.$table
                        (id, username, email, password, birthdate, role)
                        VALUES(null, :username,  :email, :password, :birthdate, :role)"
                );
                $stm->bindParam(':username', $user['username'], PDO::PARAM_STR);
                $stm->bindParam(':email', $user['email'], PDO::PARAM_STR);
                $stm->bindParam(':password', $user['password'], PDO::PARAM_STR);
                $stm->bindParam(':birthdate', $user['birthdate'], PDO::PARAM_STR);
                $stm->bindParam(':role', $user['role'], PDO::PARAM_STR);
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
            self::createTable($dbname, $table);
            self::addUsers($dbname, $table);
        }
    }

    public static function getColumnsTable($dbname, $table): array|false|null
    {
        try {
            $stm = Db::getInstance()->prepare(
                'SHOW COLUMNS FROM ' . $dbname . '.' . $table
            );
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
        return false;
    }

}
