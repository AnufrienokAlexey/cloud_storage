<?php

return [
    'mysql' => [
        'host'     => getenv('DB_HOST'),
        'dbname'   => getenv('DB_NAME'),
        'charset'  => getenv('DB_CHARSET'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASS'),
    ],
    'user_table' => 'users',
    'fillUrl' => getenv('DB_FILL_URL'),
];
