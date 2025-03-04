<?php

namespace app\Interfaces;

use app\Core\Response;

interface ResponseInterface
{
    public static function send($data): void;
}