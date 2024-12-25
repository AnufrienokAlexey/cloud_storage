<?php

use app\core\Router;

$microTime = microtime(true);

spl_autoload_register();

define("CONFIG", require_once(__DIR__ . '/app/config/config.php'));
define("ROUTES", require_once(__DIR__ . '/app/config/routes.php'));

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

Router::start();

dump(microtime(true) - $microTime);
