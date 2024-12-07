<?php

$timestamp = microtime(true);

require __DIR__ . '\..\vendor\autoload.php';

echo "Это index.php";

dump(microtime(true)-$timestamp);
