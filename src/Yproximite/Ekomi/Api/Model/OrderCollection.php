<?php

namespace Yproximite\Ekomi\Api\Model;

use Yproximite\Ekomi\Api\Exception\EmptyCollectionException;

/**
 * Class OrderCollection
 */
class OrderCollection implements CollectionInterface, \IteratorAggregate, \Countable
{
    /**
     * @var Order[]
     */
    private $orders;

    /**
     * @param Order[] $orders
     */
    public function __construct(array $orders = [])
    {
        $this->orders = array_values($orders);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->orders);
    }

    /**
     * @return Order
     * @throws EmptyCollectionException
     */
    public function first()
    {
        if (empty($this->orders)) {
            throw new EmptyCollectionException('The AddressCollection instance is empty.');
        }

        return reset($this->orders);
    }

    /**
     * @return Order[]
     */
    public function slice($offset, $length = null)
    {
        return array_slice($this->orders, $offset, $length);
    }

    /**
     * @return bool
     */
    public function has($index)
    {
        return isset($this->orders[$index]);
    }

    /**
     * @return Order
     * @throws \OutOfBoundsException
     */
    public function get($index)
    {
        if (!isset($this->orders[$index])) {
            throw new \OutOfBoundsException(
                sprintf('The index "%s" does not exist in this collection.', $index)
            );
        }

        return $this->orders[$index];
    }

    /**
     * @return Order[]
     */
    public function all()
    {
        return $this->orders;
    }
}
