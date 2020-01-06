<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Message;

use Psr\Http\Message\StreamInterface;

/**
 * Interface MessageInterface
 */
interface MessageInterface
{
    /**
     * Builds the message
     *
     * @return array|resource|string|StreamInterface|null
     */
    public function build();
}
