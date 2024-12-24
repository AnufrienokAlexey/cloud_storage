<?php

namespace app\core;

class Db
{

    public string $host;
    private string $dbname;
    private string $charset;
    private string $username;
    private string $password;

    public function __construct($host, $dbname, $charset, $username, $password)
    {
        //$this->host = $host;
        $this->dbname = $dbname;
        $this->charset = $charset;
        $this->username = $username;
        $this->password = $password;
    }

    protected function getHost(): string
    {
        return $this->host;
    }

    protected function getDbname(): string
    {
        return $this->dbname;
    }

    protected function getCharset(): string
    {
        return $this->charset;
    }

    protected function getUsername(): string
    {
        return $this->username;
    }

    protected function getPassword(): string
    {
        return $this->password;
    }
}