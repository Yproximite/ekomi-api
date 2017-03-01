<?php

namespace spec\Yproximite\Ekomi\Api\Model\Order;

use PhpSpec\ObjectBehavior;

use Yproximite\Ekomi\Api\Model\Order\OrderCustomData;

class OrderCustomDataSpec extends ObjectBehavior
{
    function let()
    {
        $data = [
            'client_id'        => '5',
            'transaction_date' => '2016-12-28T18:57:01+0000',
            'project_type'     => 'test',
            'vendor_id'        => '12345',
            'project_location' => 'Lyon',
        ];

        $this->beConstructedWith($data);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OrderCustomData::class);
    }

    function it_should_be_hydrated()
    {
        $this->getClientId()->shouldReturn('5');
        $this->getTransactionDate()->shouldBeLike(new \DateTime('2016-12-28 18:57:01'));
        $this->getProjectType()->shouldReturn('test');
        $this->getVendorId()->shouldReturn('12345');
        $this->getProjectLocation()->shouldReturn('Lyon');
    }
}
