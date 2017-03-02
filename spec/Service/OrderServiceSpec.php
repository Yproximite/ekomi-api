<?php

namespace spec\Yproximite\Ekomi\Api\Service;

use PhpSpec\ObjectBehavior;

use Yproximite\Ekomi\Api\Client\Client;
use Yproximite\Ekomi\Api\Model\Order\Order;
use Yproximite\Ekomi\Api\Factory\ModelFactory;
use Yproximite\Ekomi\Api\Service\OrderService;
use Yproximite\Ekomi\Api\Message\Order\OrderListMessage;

class OrderServiceSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(OrderService::class);
    }

    function let(Client $client, ModelFactory $factory)
    {
        $this->beConstructedWith($client, $factory);
    }

    function it_should_get_orders(
        Client $client,
        ModelFactory $factory,
        OrderListMessage $message
    ) {
        $query = [
            'offset'           => 5,
            'limit'            => 15,
            'orderBy'          => 'created',
            'orderDirection'   => 'DESC',
            'withFeedbackOnly' => true,
            'createdFrom'      => '2016-11-05T00:14:29+0000',
            'createdTill'      => '2016-11-06T00:14:29+0000',
            'shopId'           => 11,
            'customDataFilter' => json_encode(['vendor_id' => 123]),
        ];

        $message->build()->willReturn($query);

        $method = 'GET';
        $path   = 'orders';

        $client->sendRequest($method, $path, $query)->willReturn(['data' => []]);
        $client->sendRequest($method, $path, $query)->shouldBeCalled();

        $factory->createMany(Order::class, [])->willReturn([]);

        $this->getOrders($message);
    }
}
