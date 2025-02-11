<?php

namespace app\Core;

use PDO;
use PDOException;

class Db
{
    protected static ?Db $_instance = null;

    public function __construct($host, $dbname, $charset, $username, $password)
    {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        try {
            return new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return false;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInstance(): self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self(
                CONFIG['host'],
                CONFIG['dbname'],
                CONFIG['charset'],
                CONFIG['username'],
                CONFIG['password'],
            );
        }
        return self::$_instance;
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
