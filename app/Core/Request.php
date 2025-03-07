<?php

namespace app\Core;

class Request
{
    public static ?int $id = null;
    public static ?int $userId = null;
    public static ?string $configRoute = null;

    public static function getUri(): string
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $uri = '/' . trim($uri, '/');
        return trim($uri);
    }

    public static function getRoute(): array
    {
        return explode('/', self::getUri());
    }

    public static function getId(): ?int
    {
        return self::$id;
    }

    public static function getUserId(): ?int
    {
        return self::$userId;
    }

    public static function getConfigRoute(): void
    {
        $uriArray = self::getRoute();
        self::$configRoute = implode('/', $uriArray);
        $lastElement = array_pop($uriArray);

        if (is_numeric($lastElement)) {
            self::$id = $lastElement;
            $uriStr = implode('/', $uriArray);
            self::$configRoute = "$uriStr/{id}";
            $lastElement2 = array_pop($uriArray);
            if (is_numeric($lastElement2)) {
                self::$id = $lastElement2;
                self::$userId = $lastElement;
                $uriStr = implode('/', $uriArray);
                self::$configRoute = "$uriStr/{id}/{user_id}";
            }
        }
    }

    public static function getEntityBody(): array|null
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    public static function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
