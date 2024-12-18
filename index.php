<?php

use app\core\Router;

define("CONFIG", require_once(__DIR__ . '/app/config/config.php'));
define("URLLIST", require_once(__DIR__ . '/app/config/urllist.php'));

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

Router::createNewDb();

