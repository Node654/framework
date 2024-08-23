<?php

namespace Nodest\Framework\Exceptions;

class HttpException extends \Exception
{
    private int $statusCode = 404;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }
}
