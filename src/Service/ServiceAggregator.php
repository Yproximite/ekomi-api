<?php
declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Service;

use Yproximite\Ekomi\Api\Client\Client;
use Yproximite\Ekomi\Api\Factory\ModelFactory;

/**
 * Class ServiceAggregator
 */
class ServiceAggregator
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
     * @var ServiceInterface[]
     */
    private $services = [];

    /**
     * ServiceAggregator constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client       = $client;
        $this->modelFactory = new ModelFactory();
    }

    /**
     * @return OrderService
     */
    public function order(): OrderService
    {
        /** @var OrderService $service */
        $service = $this->getService(OrderService::class);

        return $service;
    }

    /**
     * @param string $class
     *
     * @return ServiceInterface
     */
    private function getService(string $class): ServiceInterface
    {
        if (!array_key_exists($class, $this->services)) {
            $this->services[$class] = new $class($this->client, $this->modelFactory);
        }

        return $this->services[$class];
    }
}
