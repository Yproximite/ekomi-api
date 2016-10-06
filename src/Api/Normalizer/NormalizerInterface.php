<?php

namespace Yproximite\Ekomi\Api\Normalizer;

/**
 * Interface NormalizerInterface
 */
interface NormalizerInterface
{
    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function normalize($data);
}
