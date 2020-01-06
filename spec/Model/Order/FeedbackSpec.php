<?php

declare(strict_types=1);

namespace spec\Yproximite\Ekomi\Api\Model\Order;

use PhpSpec\ObjectBehavior;
use Yproximite\Ekomi\Api\Model\Order\Feedback;

class FeedbackSpec extends ObjectBehavior
{
    public function let()
    {
        $data = [
            'end_customer_submit_date' => '2016-12-28T18:57:01+0000',
            'rating'                   => '5',
            'review'                   => "Hello\\nI'm user.",
            'comment'                  => 'Hello',
        ];

        $this->beConstructedWith($data);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Feedback::class);
    }

    public function it_should_be_hydrated()
    {
        $this->getEndCustomerSubmitDate()->shouldBeLike(new \DateTime('2016-12-28 18:57:01'));
        $this->getRating()->shouldReturn(5);
        $this->getReview()->shouldReturn("Hello\nI'm user.");
        $this->getComment()->shouldReturn('Hello');
    }
}
