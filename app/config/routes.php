<?php

return [
    '/users' => [
        ['GET', ['User', 'list']],
        ['PUT', ['User', 'update']],
    ],
    '/users/get' => [
        ['GET', ['User', 'get']],
    ],
    '/admin/users/list' => [
        ['GET', ['Admin', 'list']],
    ],
    '/admin/users/get/{id}' => [
        ['GET', ['Admin', 'get']],
    ],
    '/admin/users/delete/{id}' => [
        ['DELETE', ['Admin', 'delete']],
    ],
    '/admin/users/update/{id}' => [
        ['PUT', ['Admin', 'update']],
    ],
    '/files/list' => [
        ['GET', ['File', 'list']],
    ],
    '/files/get/{id}' => [
        ['GET', ['File', 'getId']],
    ],
    '/files/add' => [
        ['POST', ['File', 'add']],
    ],
    '/files/rename' => [
        ['PUT', ['File', 'rename']],
    ],
    '/files/remove/{id}' => [
        ['DELETE', ['File', 'removeId']],
    ],
    '/directories/add' => [
        ['POST', ['File', 'add']],
    ],
    '/directories/rename' => [
        ['PUT', ['File', 'rename']],
    ],
    '/directories/get/{id}' => [
        ['GET', ['File', 'getId']],
    ],
    '/directories/delete/{id}' => [
        ['DELETE', ['File', 'deleteId']],
    ],
    '/files/share/{id}' => [
        ['GET', ['File', 'shareId']],
    ],
    '/files/share/{id}/{user_id}' => [
        ['PUT', ['File', 'shareIdUserId']],
        ['DELETE', ['File', 'deleteIdUserId']],
    ],

];
