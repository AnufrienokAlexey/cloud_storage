<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

use app\Core\Connect;
use app\Core\Registry;
use app\Core\Router;
use PhpDevCommunity\DotEnv;

$microTime = microtime(true);

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

(new DotEnv(__DIR__ . '/.env'))->load();

define("CONFIG", require(__DIR__ . '/app/config/config.php'));
define("ROUTES", require_once(__DIR__ . '/app/config/routes.php'));
define("DB", Registry::getInstance()->get('mysql'));

const APP = __DIR__ . '/app';
const DS = DIRECTORY_SEPARATOR;

Connect::connect(DB['dbname'], 'users');
Router::processRequest();

dump(microtime(true) - $microTime);
