<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Message\Order;

use Yproximite\Ekomi\Api\Message\MessageInterface;

class OrderListMessage implements MessageInterface
{
    public const ORDER_BY_ORDER_ID = 'order_id';
    public const ORDER_BY_CREATED  = 'created';
    public const ORDER_BY_UPDATED  = 'updated';

    public const ORDER_DIRECTION_ASC  = 'ASC';
    public const ORDER_DIRECTION_DESC = 'DESC';

    /**
     * @var int|null
     */
    private $offset;

    /**
     * @var int|null
     */
    private $limit;

    /**
     * @var string|null
     */
    private $orderBy;

    /**
     * @var string|null
     */
    private $orderDirection;

    /**
     * @var bool|null
     */
    private $withFeedbackOnly;

    /**
     * @var \DateTime|null
     */
    private $createdFrom;

    /**
     * @var \DateTime|null
     */
    private $createdTill;

    /**
     * @var int|null
     */
    private $shopId;

    /**
     * @var array|null
     */
    private $customDataFilter;

    /**
     * @return int|null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset(int $offset = null)
    {
        $this->offset = $offset;
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit(int $limit = null)
    {
        $this->limit = $limit;
    }

    /**
     * @return string|null
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy = null)
    {
        $this->orderBy = $orderBy;
    }

    /**
     * @return string|null
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    public function setOrderDirection(string $orderDirection = null)
    {
        $this->orderDirection = $orderDirection;
    }

    /**
     * @return bool|null
     */
    public function isWithFeedbackOnly()
    {
        return $this->withFeedbackOnly;
    }

    public function setWithFeedbackOnly(bool $withFeedbackOnly = null)
    {
        $this->withFeedbackOnly = $withFeedbackOnly;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedFrom()
    {
        return $this->createdFrom;
    }

    public function setCreatedFrom(\DateTime $createdFrom = null)
    {
        $this->createdFrom = $createdFrom;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedTill()
    {
        return $this->createdTill;
    }

    public function setCreatedTill(\DateTime $createdTill = null)
    {
        $this->createdTill = $createdTill;
    }

    /**
     * @return int|null
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    public function setShopId(int $shopId = null)
    {
        $this->shopId = $shopId;
    }

    /**
     * @return array|null
     */
    public function getCustomDataFilter()
    {
        return $this->customDataFilter;
    }

    public function setCustomDataFilter(array $customDataFilter = null)
    {
        $this->customDataFilter = $customDataFilter;
    }

    public function build(): array
    {
        return [
            'offset'           => $this->getOffset(),
            'limit'            => $this->getLimit(),
            'orderBy'          => $this->getOrderBy(),
            'orderDirection'   => $this->getOrderDirection(),
            'withFeedbackOnly' => $this->isWithFeedbackOnly(),
            'createdFrom'      => $this->getCreatedFrom()?->format(\DateTime::ATOM),
            'createdTill'      => $this->getCreatedTill()?->format(\DateTime::ATOM),
            'shopId'           => $this->getShopId(),
            'customDataFilter' => $this->getCustomDataFilter() ? json_encode($this->getCustomDataFilter()) : null,
        ];
    }
}
