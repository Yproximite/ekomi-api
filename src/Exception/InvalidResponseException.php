<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Exception;

use Symfony\Contracts\HttpClient\ResponseInterface;

class InvalidResponseException extends RequestException
{
    public function __construct(
        string $message,
        private ResponseInterface $response,
        \Exception $previous = null
    ) {
        parent::__construct($message, $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
