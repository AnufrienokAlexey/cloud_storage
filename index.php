<?php

//use app\core\Router;

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
//                item поменять в цикле
                $arr = explode('::', current($item));
                debug($arr);
                $class = 'app\core\\'.$arr[0];
                $func = trim($arr[1], '()');
                debug($class);
                debug($func);
                $obj = new $class();
                $obj->$func();

            }
        }
    }
} else {
    echo 'Ошибка 404';
}