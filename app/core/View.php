<?php

namespace App\core;

class View
{
    public string $path;
    public string $route;
    public string $layout = 'default';

    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route;
    }

    public function render($title, $vars = [])
    {
        $path = 'app/views/' . $this->route . '.php';
        $header = 'app/views/header/header.php';
        if (file_exists($path)) {
            ob_start();
            if (file_exists($header)) {
                require $header;
            }
            require $path;
            $content = ob_get_clean();
            require 'app/views/layouts/' . $this->layout . '.php';
        }
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    public static function errorCode($code)
    {
        http_response_code($code);
        $path = 'app/views/errors/' . $code . '.php';
        if (file_exists($path)) {
            require $path;
        }
        exit;
    }
}