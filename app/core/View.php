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
        debug($route);
        $this->path = $route;
    }

    public function render($title, $vars = [])
    {
        extract($vars);
        $path = 'app/views/'.$this->route.'.php';
        if (file_exists($path)) {
            ob_start();
            require $path;
            $content = ob_get_clean();
            require 'app/views/layouts/'.$this->layout.'.php';
        }
    }

    public function redirect($url)
    {
        header('Location: '.$url);
        exit;
    }

    public static function errorCode($code) {
        http_response_code($code);
        $path = 'app/views/errors/'.$code.'.php';
        if (file_exists($path)) {
            require $path;
        }
        exit;
    }
}