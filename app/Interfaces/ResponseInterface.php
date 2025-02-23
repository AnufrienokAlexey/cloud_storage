<?php

namespace app\Interfaces;

use app\Core\Response;

interface ResponseInterface
{
    public function send(): void;
}