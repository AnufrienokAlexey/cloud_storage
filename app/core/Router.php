<?php

namespace app\core;

class Router
{
    public static function start(): void
    {
        Connect::connect();

        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        if (array_key_exists($uri, ROUTES)) {
            echo 'yes';
            dump($uri);
            dump($method);
            dump(ROUTES);
        } else {
            self::ErrorPage(402);
        }
    }

    public static function ErrorPage(int $status): void
    {
        http_response_code($status);
        header("HTTP/1.1 $status Not Found");
    }
}
