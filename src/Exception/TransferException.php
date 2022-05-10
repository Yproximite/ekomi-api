<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TransferException extends RequestException
{
    public function __construct(
        string $message,
        RequestInterface $request,
        private ?ResponseInterface $response = null,
        \Exception $previous = null
    ) {
        parent::__construct($message, $request, $previous);
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}
