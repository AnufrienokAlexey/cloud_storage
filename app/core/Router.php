<?php

namespace app\core;

use JetBrains\PhpStorm\NoReturn;

class Router
{
    public static function start(): void
    {
        $uri = urldecode($_SERVER['REQUEST_URI']);
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = '/' . trim($uri, '/');
        $uri = trim($uri);
        $arr = explode('/', $uri);
        $id = array_pop($arr);
        $route = implode('/', $arr);
        $id = (is_numeric($id) ? $id : null);
        dump("uri:", $uri);
        dump("route:", $route);

        if (array_key_exists($route, ROUTES)) {
            $match = false;
            foreach (ROUTES[$route] as $route) {
                if ($route[0] == $method) {
                    $match = true;
                    dump($route);
                    $controller = $route[1][0];
                    $action = $route[1][1];
                    $controllerFullName = "app\\Controllers\\" . $controller;
                    $controllerPath = APP . '/Controllers/' . $controller . '.php';
                    if (file_exists($controllerPath)) {
                        $c = new $controllerFullName();
                        dump("controller:", $c);
                        dump("action:", $action);
                        if (method_exists($c, $action)) {
                            dump($id, $c, $action);
                            ($id != null) ? $c->$action($id) : $c->$action();
                        } else {
                            dump('Controller method not found!');
                        }
                    } else {
                        dump('Controller class not found!');
                    }
                }
            }
            if (!$match) {
                self::ErrorPage(405);
            }
        } else {
            dump('Route not found!');
            self::ErrorPage(406);
        }
    }

public static function ErrorPage(int $status): void
    {
        http_response_code($status);
        header("HTTP/1.1 $status NFF");
    }
}
