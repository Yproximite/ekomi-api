<?php

namespace spec\Yproximite\Ekomi\Api\Model\Order;

use PhpSpec\ObjectBehavior;

use Yproximite\Ekomi\Api\Model\Order\Order;

class OrderSpec extends ObjectBehavior
{
    function let()
    {
        $feedback = [
            'end_customer_submit_date' => '2016-12-28T18:57:01+0000',
            'rating'                   => '5',
            'review'                   => "Hello\\nI'm user.",
            'comment'                  => 'Hello',
        ];

        $customData = [
            'client_id'        => '5',
            'transaction_date' => '2016-12-28T18:57:01+0000',
            'project_type'     => 'test',
            'vendor_id'        => '12345',
            'project_location' => 'Lyon',
        ];

        $data = [
            'created'                   => '2016-11-28T18:57:01+0000',
            'updated'                   => '2016-11-29T18:57:01+0000',
            'order_id'                  => '55',
            'first_name'                => 'Ivan',
            'last_name'                 => 'Ivanov',
            'email'                     => 'mail@gmail.com',
            'custom_data'               => $customData,
            'register_date'             => '2016-11-29T19:57:01+0000',
            'end_customer_contact_date' => '2016-12-29T19:57:01+0000',
            'product_ids'               => ['1', '2'],
            'review_link'               => 'http://review.com',
            'feedback'                  => $feedback,
        ];

        $this->beConstructedWith($data);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Order::class);
    }

    function it_should_be_hydrated()
    {
        $this->getCreated()->shouldBeLike(new \DateTime('2016-11-28 18:57:01'));
        $this->getUpdated()->shouldBeLike(new \DateTime('2016-11-29 18:57:01'));
        $this->getOrderId()->shouldReturn('55');
        $this->getFirstName()->shouldReturn('Ivan');
        $this->getLastName()->shouldReturn('Ivanov');
        $this->getEmail()->shouldReturn('mail@gmail.com');
        $this->getRegisterDate()->shouldBeLike(new \DateTime('2016-11-29 19:57:01'));
        $this->getEndCustomerContactDate()->shouldBeLike(new \DateTime('2016-12-29 19:57:01'));
        $this->getProductIds()->shouldReturn(['1', '2']);
        $this->getReviewLink()->shouldReturn('http://review.com');
    }
}
