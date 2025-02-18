<?php

namespace app\Core;

class Router
{
    public function processRequest()
    {
    }

    public static function start(): void
    {
        $id = Request::getId();
        dump($id);
        $uri = Request::getUri();
        dump($uri);
        $route = Request::getRoute();
        dump($route);
        $method = Request::getMethod();
        dump($method);

        if ($id != null) {
            $uriArr = explode('/', $uri);
            array_pop($uriArr);
            $uriStr = implode('/', $uriArr);
            $uri = "$uriStr/{id}";
        }

        if (array_key_exists($uri, ROUTES)) {
            $matchMethod = false;
            foreach (ROUTES[$uri] as $route) {
                if ($route[0] == $method) {
                    $matchMethod = true;
                    $controller = $route[1][0];
                    dump($controller);
                    $action = $route[1][1];
                    $controllerFullName = "app\\Controllers\\" . $controller;
                    $controllerPath = APP . '/Controllers/' . $controller . '.php';
                    if (file_exists($controllerPath)) {
                        $c = new $controllerFullName();
                        if (method_exists($c, $action)) {
                            ($id != null) ? $c->$action($id) : $c->$action();
                        } else {
                            dump('Controller action not found!');
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
