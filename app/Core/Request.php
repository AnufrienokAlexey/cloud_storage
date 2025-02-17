<?php

namespace app\Core;

class Request
{
    private array $storage;

    public function __construct()
    {
        $this->storage = $this->cleanInput($_REQUEST);
    }

    public function __get($name)
    {
        if (isset($this->storage[$name])) {
            return $this->storage[$name];
        }
    }

    public function getData(): array
    {
        return $this->storage;
    }

    public function getRoute(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    private function cleanInput($data): array
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
