<?php

namespace app\core;

use PDO;

class Connect extends Db
{
    //protected static string $dbName = 'cloud_storage';

    public static function db(): PDO
    {
        $db = new Db(
            CONFIG['host'],
            CONFIG['dbname'],
            CONFIG['charset'],
            CONFIG['username'],
            CONFIG['password'],
        );
        $dbVars = get_object_vars($db);

        try {
            return new PDO(
                "mysql:host=$dbVars[host]",
                "$dbVars[username]",
                "$dbVars[password]"
            );
        } catch (\Exception $e) {
            error_log($e->getMessage());
            die('MySQL is not connected!');
        }
    }

    public static function getAllDatabases(): false|array
    {
        return self::db()
            ->query('SHOW DATABASES')
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function createNewDb($dbname): void
    {
//        $host = self::db()->host;
//        $username = self::db()->username;
//        $password = self::db()->password;
//
//        try {
//            $pdo = new PDO(
//                "mysql:host=$host",
//                "$username",
//                "$password"
//            );
//            $stm = $pdo->prepare(
//                "CREATE DATABASE $dbname COLLATE utf8_general_ci"
//            );
//            $stm->execute();
//        } catch (\PDOException $e) {
//            error_log($e->getMessage());
//        }
        try {
            self::db()->prepare(
                "CREATE DATABASE $dbname COLLATE utf8_general_ci"
            );
        } catch (\PDOException $e) {
            error_log($e->getMessage());
        }
    }

    public static function connect(): void
    {
        $databases = self::getAllDatabases();

        if (!in_array(CONFIG['dbname'], $databases)) {
            self::createNewDb(CONFIG['dbname']);
        }

        $pdo = self::db();
        $stm = $pdo->prepare("SELECT * FROM `cloud_storage`");
        dump($stm);
        $stm->execute();
    }
}
