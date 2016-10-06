<?php

namespace Yproximite\Ekomi\Api\Request;

use Yproximite\Ekomi\Api\Model\OrderCollection;
use Yproximite\Ekomi\Api\Normalizer\OrderNormalizer;
use Yproximite\Ekomi\Api\Normalizer\CollectionNormalizer;

/**
 * Class GetOrdersRequest
 */
class GetOrdersRequest extends AbstractRequest implements RequestInterface
{
    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var string
     */
    private $orderBy;

    /**
     * @var string
     */
    private $orderDirection;

    /**
     * @var bool
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
     * {@inheritdoc}
     */
    public function getPath()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return [
            'offset'           => $this->getOffset(),
            'limit'            => $this->getLimit(),
            'orderBy'          => $this->getOrderBy(),
            'orderDirection'   => $this->getOrderDirection(),
            'withFeedbackOnly' => $this->isWithFeedbackOnly(),
            'createdFrom'      => $this->getCreatedFrom() ? $this->getCreatedFrom()->format(\DateTime::ISO8601) : null,
            'createdTill'      => $this->getCreatedTill() ? $this->getCreatedTill()->format(\DateTime::ISO8601) : null,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseNormalizer()
    {
        return new CollectionNormalizer(new OrderCollection(), new OrderNormalizer());
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     *
     * @return GetOrdersRequest
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return GetOrdersRequest
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     *
     * @return GetOrdersRequest
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * @param string $orderDirection
     *
     * @return GetOrdersRequest
     */
    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isWithFeedbackOnly()
    {
        return $this->withFeedbackOnly;
    }

    /**
     * @param boolean $withFeedbackOnly
     *
     * @return GetOrdersRequest
     */
    public function setWithFeedbackOnly($withFeedbackOnly)
    {
        $this->withFeedbackOnly = $withFeedbackOnly;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedFrom()
    {
        return $this->createdFrom;
    }

    /**
     * @param \DateTime|null $createdFrom
     *
     * @return GetOrdersRequest
     */
    public function setCreatedFrom(\DateTime $createdFrom = null)
    {
        $this->createdFrom = $createdFrom;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedTill()
    {
        return $this->createdTill;
    }

    /**
     * @param \DateTime|null $createdTill
     *
     * @return GetOrdersRequest
     */
    public function setCreatedTill(\DateTime $createdTill = null)
    {
        $this->createdTill = $createdTill;

        return $this;
    }
}
