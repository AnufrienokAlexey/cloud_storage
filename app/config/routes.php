<?php

return [
    '/users' => [
        ['GET', 'list'],
        ['PUT', 'update'],
    ],
    '/users/id' => [
        ['GET', 'get'],
    ],
    '/admin/users' => [
        ['GET', 'list'],
    ],
    '/admin/users/id' => [
        ['GET', 'get'],
        ['DELETE', 'delete'],
        ['PUT', 'update'],
    ],
    '/files' => [
        ['GET', 'list'],
        ['POST', 'add'],
        ['PUT', 'rename'],
    ],
    '/files/id' => [
        ['GET', 'get'],
        ['DELETE', 'remove'],
    ],
    '/files/id/user_id' => [
        ['PUT', 'share'],
        ['DELETE', 'share'],
    ],
    '/directories' => [
        ['POST', 'add'],
        ['PUT', 'rename'],
    ],
    '/directories/id' => [
        ['GET', 'get'],
        ['DELETE', 'delete'],
    ],
    '/directories/id/user_id' => [
        ['PUT', 'share'],
        ['DELETE', 'share'],
    ],
];
