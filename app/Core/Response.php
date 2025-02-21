<?php

namespace app\Core;

use app\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    private string $data;
    private int $statusCode;

    public function __construct(
        string $data = 'null',
        int $status_code = 201,
    ) {
        $this->setData($data);
        $this->setStatusCode($status_code);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function send(): string
    {
        http_response_code($this->getStatusCode());
        return json_encode(
            [
                'status_code' => $this->getStatusCode(),
                'data' => $this->getData()
            ]
        );
    }

    private function setData(string $data): void
    {
        $this->data = $data;
    }

    private function setStatusCode(int $status_code): void
    {
        $this->statusCode = $status_code;
    }
}
