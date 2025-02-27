<?php

namespace app\Core;

use app\Interfaces\ResponseInterface;
use JetBrains\PhpStorm\NoReturn;

class Response implements ResponseInterface
{
    #[NoReturn] public static function send(
        $data = null,
        $id = null,
        $userId = null,
        $statusCode = 200
    ): void {
        header_remove();
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'status_code' => $statusCode,
            'id' => $id,
            'user_id' => $userId,
            'data' => $data,
        ]);
        exit();
    }
}
