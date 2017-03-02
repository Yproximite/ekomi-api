<?php
declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Service;

use Yproximite\Ekomi\Api\Model\Order\Order;
use Yproximite\Ekomi\Api\Message\Order\OrderListMessage;

/**
 * Class OrderService
 */
class OrderService extends AbstractService implements ServiceInterface
{
    /**
     * @param OrderListMessage $message
     *
     * @return Order[]
     */
    public function getOrders(OrderListMessage $message): array
    {
        $path = 'orders';

        $response = $this->getClient()->sendRequest('GET', $path, $message->build());

        /** @var Order[] $models */
        $models = $this->getModelFactory()->createMany(Order::class, $response['data']);

        return $models;
    }
}
