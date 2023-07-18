<?php

namespace App\core;

class Router
{
    protected array $routes = [];

    public function __construct()
    {
        $routes = require 'app/config/routes.php';
        $this->routes = $routes;
        $this->run();
    }

    public function run()
    {
        $parseUrl = parse_url($_SERVER['REQUEST_URI']);
        $parseUrl = trim($parseUrl['path'], '/');
        $urlArray = explode('/', $parseUrl, 3);
        $url = $urlArray[0] . '/' . $urlArray[1];
        $id = $urlArray[2];
        debug($id);

        if (array_key_exists($url, $this->routes)) {
            foreach ($this->routes as $route => $value) {
                if ($route === $url) {
                    foreach ($value as $method => $params) {
                        if ($method === $_SERVER['REQUEST_METHOD']) {
                            $arr = explode('::', $params);
                            $class = 'app\controllers\\' . ucfirst($arr[0]) . 'Controller';
                            $func = trim($arr[1], '()');
                            if (!empty($id)) {
//                                надо использовать preg_match и [0-9]...
                                if ((int)$id !== 0) {
                                    $obj = new $class($url, $id);
                                } else {
                                    View::errorCode(406);
                                }
                            } else {
                                $obj = new $class($url);
                            }
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
}
