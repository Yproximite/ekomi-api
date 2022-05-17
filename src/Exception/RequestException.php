<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Exception;

class RequestException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(
        string $message,
        \Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }
}
