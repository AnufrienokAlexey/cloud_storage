<?php

namespace app\core;

class Router
{
    public static function start(): void
    {
        Connect::connect();

        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = '/' . trim($uri, '/');

        if (array_key_exists($uri, ROUTES)) {
            $match = false;
            foreach (ROUTES[$uri] as $value) {
                if ($value[0] == $method) {
                    $match = true;
                    $controller = explode('/', $uri);
                    $controller = ucfirst($controller[1]) . '.php';
                    dump($controller);
                    if (file_exists('/../controllers/' . $controller)) {
                        dump('match');
                    }
                }
            }
            if (!$match) {
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
