<?php

namespace app\Core;

use PDO;
use PDOException;

class Db
{
    protected static ?Db $_instance = null;
    private PDO $pdo;

    private function __construct($host, $username, $password)
    {
        $dsn = "mysql:host=$host";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getInstance(): PDO
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self(
                DB['host'],
                DB['username'],
                DB['password'],
            );
        }

        return self::$_instance->pdo;
    }

    public function getConnection()
    {
    }

    public function findBy()
    {
    }

    public function findOneBy()
    {
    }

    public function findAll()
    {
    }

    public function find()
    {
    }
}
