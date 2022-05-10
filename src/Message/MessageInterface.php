<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Message;

use Psr\Http\Message\StreamInterface;

interface MessageInterface
{
    /**
     * @return array|resource|string|StreamInterface|null
     */
    public function build();
}
