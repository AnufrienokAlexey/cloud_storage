<?php

use app\core\Router;
use app\core\Connect;

define("CONFIG", require_once(__DIR__ . '/app/config/config.php'));
define("URLLIST", require_once(__DIR__ . '/app/config/urllist.php'));

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

Connect::createNewDb();
