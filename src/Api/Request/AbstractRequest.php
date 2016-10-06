<?php

namespace Yproximite\Ekomi\Api\Request;

/**
 * Class AbstractRequest
 */
abstract class AbstractRequest
{
    /**
     * @return string
     */
    public function getMethod()
    {
        return 'GET';
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        return [];
    }
}
