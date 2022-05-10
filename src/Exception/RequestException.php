<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Exception;

use Psr\Http\Message\RequestInterface;

class RequestException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(
        string $message,
        private RequestInterface $request,
        \Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
