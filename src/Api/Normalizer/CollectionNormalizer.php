<?php

namespace Yproximite\Ekomi\Api\Normalizer;

use Yproximite\Ekomi\Api\Model\CollectionInterface;
use Yproximite\Ekomi\Api\Exception\InvalidArgumentException;

/**
 * Class CollectionNormalizer
 */
class CollectionNormalizer implements NormalizerInterface
{
    /**
     * @var string
     */
    private $collectionClass;

    /**
     * @var NormalizerInterface
     */
    private $itemNormalizer;

    /**
     * TraversableNormalizer constructor.
     *
     * @param string              $collectionClass
     * @param NormalizerInterface $itemNormalizer
     */
    public function __construct($collectionClass, NormalizerInterface $itemNormalizer)
    {
        $this->collectionClass = $collectionClass;
        $this->itemNormalizer  = $itemNormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new InvalidArgumentException('Data must be an array or an instance of \\Traversable.');
        }

        $items = [];

        foreach ($data as $item) {
            $items[] = $this->itemNormalizer->normalize($item);
        }

        return new $this->collectionClass($items);
    }
}
