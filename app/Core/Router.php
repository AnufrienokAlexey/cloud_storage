<?php

namespace app\Core;

class Router
{
    public function processRequest()
    {

    }

    public static function start(): void
    {
        $uri = urldecode($_SERVER['REQUEST_URI']);
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = '/' . trim($uri, '/');
        $uri = trim($uri);
        $arrUri = explode('/', $uri);
        $route = (!empty($arrUri[2])) ? "/$arrUri[1]/$arrUri[2]" : "/$arrUri[1]";
        $id = is_numeric($arrUri[3]) ? $arrUri[3] : null;

        if (array_key_exists($route, ROUTES)) {
            $matchMethod = false;
            foreach (ROUTES[$route] as $route) {
                if ($route[0] == $method) {
                    $matchMethod = true;
                    $controller = $route[1][0];
                    $action = $route[1][1];
                    $controllerFullName = "app\\Controllers\\" . $controller;
                    $controllerPath = APP . '/Controllers/' . $controller . '.php';
                    if (file_exists($controllerPath)) {
                        $c = new $controllerFullName();
                        if (method_exists($c, $action)) {
                            ($id != null) ? $c->$action($id) : $c->$action();
                        } else {
                            dump('Controller method not found!');
                        }
                    } else {
                        dump('Controller class not found!');
                    }
                }
            }
            if (!$matchMethod) {
                self::ErrorPage(405);
            }
        } else {
            self::ErrorPage(404);
        }
    }

public static function ErrorPage(int $status): void
    {
        http_response_code($status);
        header("HTTP/1.1 $status");
    }
}
