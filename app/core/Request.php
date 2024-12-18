<?php

namespace App\core;

class Request
{
    public string $uri;

    public function __construct($uri)
    {
        $this->uri = trim(urldecode($uri), '/');
    }

    public function getData()
    {
    }

    public function getRoute()
    {
    }

    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }
}
