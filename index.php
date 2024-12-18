<?php

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

$urlList = [
    '/users' => [
        ['GET', 'list'],
        ['GET', 'get'],
        ['PUT', 'update'],
    ],
    '/admin/users' => [
        ['GET', 'list'],
        ['GET', 'get'],
        ['DELETE', 'delete'],
        ['PUT', 'update'],
    ],
    '/files' => [
        ['GET', 'list'],
        ['GET', 'get'],
        ['POST', 'add'],
        ['PUT', 'rename'],
        ['DELETE', 'remove'],
        ['GET', 'share'],
        ['PUT', 'share'],
        ['DELETE', 'share'],
    ],
    '/directories' => [
        ['POST', 'add'],
        ['PUT', 'rename'],
        ['GET', 'get'],
        ['DELETE', 'delete'],
    ],
];

return $urlList;
