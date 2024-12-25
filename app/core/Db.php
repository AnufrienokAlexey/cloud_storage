<?php

namespace app\core;

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
}
