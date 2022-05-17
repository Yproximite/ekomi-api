<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Message;

interface MessageInterface
{
    /**
     * @return array<string, mixed>
     */
    public function build(): array;
}
