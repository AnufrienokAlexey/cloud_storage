<?php

namespace app\Core;

use InvalidArgumentException;

class Registry
{
    protected static ?Registry $instances = null;
    protected static array $registry = [];

    public function __construct()
    {
        foreach (CONFIG as $name => $routes) {
            self::set($name, $routes);
        }
    }

    public static function getInstance(): ?Registry
    {
        if (is_null(self::$instances)) {
            self::$instances = new Registry();
        }
        return self::$instances;
    }

    final public function set(string $key, $value): void
    {
        if (in_array($key, self::$registry)) {
            throw new InvalidArgumentException("Указан неверный ключ - $key.");
        }
        self::$registry[$key] = $value;
    }

    final public function get(string $key)
    {
        if (in_array($key, self::$registry)) {
            throw new InvalidArgumentException("Указан неверный ключ - $key.");
        }
        return self::$registry[$key] ?? null;
    }

}