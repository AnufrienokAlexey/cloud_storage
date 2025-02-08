<?php

error_reporting(E_ALL);

use app\core\Router;
use app\core\Connect;

$microTime = microtime(true);

spl_autoload_register();

define("CONFIG", require_once(__DIR__ . '/app/config/config.php'));
define("ROUTES", require_once(__DIR__ . '/app/config/routes.php'));
const APP = __DIR__ . '/app';

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

Router::start();
//Connect::connect();

dump(microtime(true) - $microTime);
