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
     * @var CollectionInterface
     */
    private $collection;

    /**
     * @var NormalizerInterface
     */
    private $itemNormalizer;

    /**
     * TraversableNormalizer constructor.
     *
     * @param CollectionInterface $collection
     * @param NormalizerInterface $itemNormalizer
     */
    public function __construct(CollectionInterface $collection, NormalizerInterface $itemNormalizer)
    {
        $this->collection     = $collection;
        $this->itemNormalizer = $itemNormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data)
    {
        if (!is_array($data) && !$data instanceof \Traversable) {
            throw new InvalidArgumentException('Data must be an array or an instance of \\Traversable.');
        }

        foreach ($data as $item) {
            $this->collection[] = $this->itemNormalizer->normalize($item);
        }

        return $this->collection;
    }
}
