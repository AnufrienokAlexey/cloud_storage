<?php

return [
    'users/list' => [
        'GET' => 'User::list()',
    ],
    'users/get' => [
        'GET' => 'User::get()',
    ],
    'users/update' => [
        'PUT' => 'User::update()',
    ],
    'users/login' => [
        'POST' => 'User::login()',
    ],
    'users/logout' => [
        'POST' => 'User::logout()',
    ],
    'users/reset_password' => [
        'POST' => 'User::reset_password()',
    ],
];