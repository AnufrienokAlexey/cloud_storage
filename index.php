<?php
$urlList = [
    'funds' => [
        'GET' => 'Funds::list()',
        'POST' => 'Funds::add()'
    ]
];
var_dump($_SERVER['REQUEST_URI']); // /test