<?php
declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Factory;

use Yproximite\Ekomi\Api\Model\ModelInterface;

/**
 * Class ModelFactory
 */
class ModelFactory
{
    /**
     * @param string $class
     * @param array  $data
     *
     * @return ModelInterface
     */
    public function create(string $class, array $data): ModelInterface
    {
        return new $class($data);
    }

    /**
     * @param string $class
     * @param array  $list
     *
     * @return ModelInterface[]
     */
    public function createMany(string $class, array $list): array
    {
        return array_map(function (array $data) use ($class) {
            return self::create($class, $data);
        }, $list);
    }
}
