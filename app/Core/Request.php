<?php

namespace app\Core;

class Request
{
    public static function getData(): array
    {
        return self::cleanInput($_REQUEST);
    }

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

    public static function getId(): ?string
    {
        $uri = self::getRoute();
        $id = array_pop($uri);
        return is_numeric($id) ? $id : null;
    }

    public static function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    private static function cleanInput($data): array
    {
        $cleanedData = [];
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $cleanedData[$key] = trim($value);
                $cleanedData[$key] = stripslashes($cleanedData[$key]);
                $cleanedData[$key] = htmlspecialchars($cleanedData[$key], ENT_QUOTES);
            }
        }
        return $cleanedData;
    }
}
