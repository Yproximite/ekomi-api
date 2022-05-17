<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Factory;

use Yproximite\Ekomi\Api\Model\ModelInterface;

class ModelFactory
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(string $class, array $data): ModelInterface
    {
        return new $class($data);
    }

    /**
     * @param array<string, mixed> $list
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
