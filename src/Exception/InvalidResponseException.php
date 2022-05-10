<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class InvalidResponseException extends RequestException
{
    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(
        string $message,
        RequestInterface $request,
        ResponseInterface $response,
        \Exception $previous = null
    ) {
        parent::__construct($message, $request, $previous);

        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
