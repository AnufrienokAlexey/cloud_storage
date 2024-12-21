<?php

namespace app\core;

class Router
{
    public static function start(): void
    {
        Connect::connect();

        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        dump($_GET['id']);

        if (array_key_exists($uri, ROUTES)) {
            $match = false;
            foreach (ROUTES[$uri] as $key => $value) {
                if (in_array($method, $value)) {
                    $match = true;
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
