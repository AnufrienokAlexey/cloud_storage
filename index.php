<?php

use app\core\Router;

require 'app\lib\dev.php';

spl_autoload_register(function ($class)
{
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }
});

$urlList = [
    'funds' => [
        'GET' => 'Funds::list()',
        'POST' => 'Funds::add()'
    ]
];

$url = trim($_SERVER['REQUEST_URI'], '/');

if (array_key_exists($url, $urlList)) {
    foreach ($urlList as $item) {
        foreach (array_keys($item) as $method) {
            if ($method == $_SERVER['REQUEST_METHOD']) {
                echo $method;
                $obj = new Router();
                $obj->list();
                return;
            }
        }
    }
} else {
    echo 'Ошибка 404';
}