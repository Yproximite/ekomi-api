<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TransferException extends RequestException
{
    /**
     * @var ResponseInterface|null
     */
    private $response;

    public function __construct(
        string $message,
        RequestInterface $request,
        ResponseInterface $response = null,
        \Exception $previous = null
    ) {
        parent::__construct($message, $request, $previous);

        $this->response = $response;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}
