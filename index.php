<?php

error_reporting(E_ALL);

use app\Core\Request;
use app\Core\Response;
use app\Core\Router;
use app\Core\Db;

$microTime = microtime(true);

spl_autoload_register();

define("CONFIG", require_once(__DIR__ . '/app/config/config.php'));
define("ROUTES", require_once(__DIR__ . '/app/config/routes.php'));
const APP = __DIR__ . '/app';

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';


$response = new Response();

Router::start();
//dump(Db::getInstance());

dump(microtime(true) - $microTime);
