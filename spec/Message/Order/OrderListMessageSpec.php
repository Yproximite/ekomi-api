<?php

declare(strict_types=1);

namespace spec\Yproximite\Ekomi\Api\Message\Order;

use PhpSpec\ObjectBehavior;
use Yproximite\Ekomi\Api\Message\Order\OrderListMessage;

class OrderListMessageSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(OrderListMessage::class);
    }

    public function it_should_build()
    {
        $this->setOffset(5);
        $this->setLimit(15);
        $this->setOrderBy(OrderListMessage::ORDER_BY_CREATED);
        $this->setOrderDirection(OrderListMessage::ORDER_DIRECTION_DESC);
        $this->setWithFeedbackOnly(true);
        $this->setCreatedFrom(new \DateTime('2016-11-05 00:14:29'));
        $this->setCreatedTill(new \DateTime('2016-11-06 00:14:29'));
        $this->setShopId(11);
        $this->setCustomDataFilter(['vendor_id' => 123]);

        $data = [
            'offset'           => 5,
            'limit'            => 15,
            'orderBy'          => 'created',
            'orderDirection'   => 'DESC',
            'withFeedbackOnly' => true,
            'createdFrom'      => '2016-11-05T00:14:29+00:00',
            'createdTill'      => '2016-11-06T00:14:29+00:00',
            'shopId'           => 11,
            'customDataFilter' => json_encode(['vendor_id' => 123]),
        ];

        $this->build()->shouldReturn($data);
    }
}
