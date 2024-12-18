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
        $databases = self::getAllDatabases();
        dump($databases);

        if (in_array('cloud_storage', $databases)) {
            dump('Already created db');
        } else {
            dump('need create');
        }
        /*
        try {
            $statement = new \PDO(
                ''
            );
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        */
    }
}
