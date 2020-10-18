<?php declare(strict_types=1);

namespace Restive\Exceptions;

class ApiException extends \Exception
{
    protected $statusCode = 400;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
