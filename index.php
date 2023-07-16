<?php

use App\core\Router;

require 'app\lib\dev.php';
require 'vendor\autoload.php';

spl_autoload_register(function ($class)
{
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }
});

$obj = new Router();
$obj->run();