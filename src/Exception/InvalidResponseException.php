<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class InvalidResponseException extends RequestException
{
    public function __construct(
        string $message,
        RequestInterface $request,
        private ResponseInterface $response,
        \Exception $previous = null
    ) {
        parent::__construct($message, $request, $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
