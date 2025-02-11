<?php

error_reporting(E_ALL);

use app\Core\Router;
use app\Core\Db;
use app\Core;
use app\Core\Connect;

$microTime = microtime(true);

spl_autoload_register();

define("CONFIG", require_once(__DIR__ . '/app/config/config.php'));
define("ROUTES", require_once(__DIR__ . '/app/config/routes.php'));
const APP = __DIR__ . '/app';

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

Router::start();
dump(Db::getInstance());
//Connect::connect();

dump(microtime(true) - $microTime);
