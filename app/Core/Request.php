<?php

namespace app\Core;

class Request
{
    public ?int $id = null;
    public ?int $userId = null;
    public ?string $configRoute = null;

    public function __construct()
    {
        $this->getConfigRoute();
    }

    public function getUri(): string
    {
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $uri = '/' . trim($uri, '/');
        return trim($uri);
    }

    public function getRoute(): array
    {
        return explode('/', $this->getUri());
    }

    public function getConfigRoute(): void
    {
        $uriArray = $this->getRoute();
        $this->configRoute = implode('/', $uriArray);
        $lastElement = array_pop($uriArray);

        if (is_numeric($lastElement)) {
            $this->id = $lastElement;
            $uriStr = implode('/', $uriArray);
            $this->configRoute = "$uriStr/{id}";
            $lastElement2 = array_pop($uriArray);
            if (is_numeric($lastElement2)) {
                $this->id = $lastElement2;
                $this->userId = $lastElement;
                $uriStr = implode('/', $uriArray);
                $this->configRoute = "$uriStr/{id}/{user_id}";
            }
        }
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
