<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Service;

use Yproximite\Ekomi\Api\Client\Client;
use Yproximite\Ekomi\Api\Factory\ModelFactory;

abstract class AbstractService
{
    public function __construct(private readonly Client $client, private readonly ModelFactory $modelFactory)
    {
    }

    protected function getClient(): Client
    {
        return $this->client;
    }

    protected function getModelFactory(): ModelFactory
    {
        return $this->modelFactory;
    }
}
