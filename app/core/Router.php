<?php

namespace App\core;

class Router
{
    protected array $routes = [];
    public function __construct()
    {
        $routes = require 'app/config/routes.php';
        $this->routes = $routes;
    }

    public function match()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');

        if (array_key_exists($url, $this->routes)) {
            foreach ($this->routes as $key => $route) {
                if ($key == $url) {
                    foreach ($route as $method => $params) {
                        if ($method == $_SERVER['REQUEST_METHOD']) {
                            $arr = explode('::', $params);
                            $class = 'app\controllers\\' . ucfirst($arr[0]) . 'Controller';
                            $func = trim($arr[1], '()');
                            $obj = new $class();
                            $obj->$func();
                        } else {
                            View::errorCode(405);
                        }
                    }
                }
            }
        } else {
            View::errorCode(404);
        }
    }

    public function run()
    {
        $this->match();
    }
}