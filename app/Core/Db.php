<?php

namespace app\Core;

use PDO;

class Db
{
    protected string $host;
    protected string $dbname;
    protected string $charset;
    protected string $username;
    protected string $password;

    public function __construct($host, $dbname, $charset, $username, $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->charset = $charset;
        $this->username = $username;
        $this->password = $password;
    }

    public function __get($name): string
    {
        return $this->$name;
    }

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    public function getConnection(): false|\PDO
    {
        try {
            return new \PDO($this->host, $this->username, $this->password);
        }
        catch (\PDOException $e) {

        }
        return false;
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
