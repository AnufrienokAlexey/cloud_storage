<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use app\Core\Request;
use app\Core\Response;
use app\Core\Router;
use app\Core\Db;

$microTime = microtime(true);

define("CONFIG", require_once(__DIR__ . '/app/config/config.php'));
define("ROUTES", require_once(__DIR__ . '/app/config/routes.php'));
const APP = __DIR__ . '/app';

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

Router::processRequest();
//dump(Db::getInstance());

dump(microtime(true) - $microTime);
