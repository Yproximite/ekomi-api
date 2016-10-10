<?php

namespace Yproximite\Ekomi\Api\Model;

/**
 * Class Order
 */
class Order
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
     * @var \DateTime
     */
    private $endCustomerContactDate;

    /**
     * @var array
     */
    private $productIds;

    /**
     * @var string
     */
    private $reviewLink;

    /**
     * @var Feedback
     */
    private $feedback;

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     *
     * @return Order
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     *
     * @return Order
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     *
     * @return Order
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return Order
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return Order
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return OrderCustomData
     */
    public function getCustomData()
    {
        return $this->customData;
    }

    /**
     * @param OrderCustomData $customData
     *
     * @return Order
     */
    public function setCustomData(OrderCustomData $customData)
    {
        $this->customData = $customData;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    /**
     * @param \DateTime $registerDate
     *
     * @return Order
     */
    public function setRegisterDate(\DateTime $registerDate)
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndCustomerContactDate()
    {
        return $this->endCustomerContactDate;
    }

    /**
     * @param \DateTime $endCustomerContactDate
     *
     * @return Order
     */
    public function setEndCustomerContactDate(\DateTime $endCustomerContactDate)
    {
        $this->endCustomerContactDate = $endCustomerContactDate;

        return $this;
    }

    /**
     * @return array
     */
    public function getProductIds()
    {
        return $this->productIds;
    }

    /**
     * @param array $productIds
     *
     * @return Order
     */
    public function setProductIds(array $productIds)
    {
        $this->productIds = $productIds;

        return $this;
    }

    /**
     * @return string
     */
    public function getReviewLink()
    {
        return $this->reviewLink;
    }

    /**
     * @param string $reviewLink
     *
     * @return Order
     */
    public function setReviewLink($reviewLink)
    {
        $this->reviewLink = $reviewLink;

        return $this;
    }

    /**
     * @return Feedback
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * @param Feedback $feedback
     *
     * @return Order
     */
    public function setFeedback(Feedback $feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }
}
