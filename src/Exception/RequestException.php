<?php
declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Exception;

use Psr\Http\Message\RequestInterface;

/**
 * Class RequestException
 */
class RequestException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param string           $message
     * @param RequestInterface $request
     * @param \Exception|null  $previous
     */
    public function __construct(
        string $message,
        RequestInterface $request,
        \Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);

        $this->request = $request;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
