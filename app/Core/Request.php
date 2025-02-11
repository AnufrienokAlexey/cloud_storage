<?php

namespace app\Core;

class Request
{
    public function getData()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function getRoute()
    {

    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}