<?php

namespace Yproximite\Ekomi\Api\Request;

use Yproximite\Ekomi\Api\Normalizer\NormalizerInterface;

/**
 * Interface RequestInterface
 */
interface RequestInterface
{
    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getPath();

    /**
     * @return array
     */
    public function getQuery();

    /**
     * @return NormalizerInterface
     */
    public function getResponseNormalizer();
}
