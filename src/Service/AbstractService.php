<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Service;

use Yproximite\Ekomi\Api\Client\Client;
use Yproximite\Ekomi\Api\Factory\ModelFactory;

/**
 * Class AbstractService
 */
abstract class AbstractService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ModelFactory
     */
    private $modelFactory;

    /**
     * AbstractService constructor.
     */
    public function __construct(Client $client, ModelFactory $modelFactory)
    {
        $this->client       = $client;
        $this->modelFactory = $modelFactory;
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
