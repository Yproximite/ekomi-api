<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Service;

use Yproximite\Ekomi\Api\Message\Order\OrderListMessage;
use Yproximite\Ekomi\Api\Model\Order\Order;

/**
 * Class OrderService
 */
class OrderService extends AbstractService implements ServiceInterface
{
    /**
     * @return Order[]
     */
    public function getOrders(OrderListMessage $message): array
    {
        $path = 'orders';

        $response = $this->getClient()->sendRequest('GET', $path, $message->build());
        $data     = $response['data'];

        while ($response['limit'] > $response['total'] && $response['total'] > $message->getOffset() + 200) {
            $message->setOffset($message->getOffset() + 200);
            $response = $this->getClient()->sendRequest('GET', $path, $message->build());
            $data     = array_merge($data, $response['data']);
        }

        /** @var Order[] $models */
        $models = $this->getModelFactory()->createMany(Order::class, $data);

        return $models;
    }
}
