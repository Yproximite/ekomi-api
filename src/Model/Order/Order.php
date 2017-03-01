<?php
declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Model\Order;

use Yproximite\Ekomi\Api\Model\ModelInterface;

/**
 * Class Order
 */
class Order implements ModelInterface
{
    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var string
     */
    private $orderId;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var OrderCustomData
     */
    private $customData;

    /**
     * @var \DateTime
     */
    private $registerDate;

    /**
     * @var \DateTime|null
     */
    private $endCustomerContactDate;

    /**
     * @var string[]
     */
    private $productIds;

    /**
     * @var string|null
     */
    private $reviewLink;

    /**
     * @var Feedback
     */
    private $feedback;

    /**
     * Order constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->created      = new \DateTime($data['created']);
        $this->updated      = new \DateTime($data['updated']);
        $this->orderId      = (string) $data['order_id'];
        $this->firstName    = (string) $data['first_name'];
        $this->lastName     = (string) $data['last_name'];
        $this->email        = (string) $data['email'];
        $this->customData   = new OrderCustomData($data['custom_data']);
        $this->registerDate = new \DateTime($data['register_date']);

        $this->endCustomerContactDate = array_key_exists('end_customer_contact_date', $data)
            ? new \DateTime($data['end_customer_contact_date'])
            : null;

        $this->productIds = array_map('strval', $data['product_ids']);
        $this->reviewLink = array_key_exists('review_link', $data) ? (string) $data['review_link'] : null;
        $this->feedback   = new Feedback($data['feedback']);
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return OrderCustomData
     */
    public function getCustomData(): OrderCustomData
    {
        return $this->customData;
    }

    /**
     * @return \DateTime
     */
    public function getRegisterDate(): \DateTime
    {
        return $this->registerDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndCustomerContactDate()
    {
        return $this->endCustomerContactDate;
    }

    /**
     * @return string[]
     */
    public function getProductIds(): array
    {
        return $this->productIds;
    }

    /**
     * @return string|null
     */
    public function getReviewLink()
    {
        return $this->reviewLink;
    }

    /**
     * @return Feedback
     */
    public function getFeedback(): Feedback
    {
        return $this->feedback;
    }
}
