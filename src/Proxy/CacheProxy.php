<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Proxy;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class CacheProxy implements CacheItemPoolInterface
{
    private $cache;

    public function __construct(CacheItemPoolInterface $cache = null)
    {
        $this->cache = $cache ?? new ArrayAdapter();
    }

    public function getItem($key): CacheItemInterface
    {
        return $this->cache->getItem($key);
    }

    public function getItems(array $keys = []): iterable
    {
        return $this->cache->getItems($keys);
    }

    public function hasItem($key): bool
    {
        return $this->cache->hasItem($key);
    }

    public function clear(): bool
    {
        return $this->cache->clear();
    }

    public function deleteItem($key): bool
    {
        return $this->cache->deleteItem($key);
    }

    public function deleteItems(array $keys): bool
    {
        return $this->cache->deleteItems($keys);
    }

    public function save(CacheItemInterface $item): bool
    {
        return $this->cache->save($item);
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        return $this->cache->saveDeferred($item);
    }

    public function commit(): bool
    {
        return $this->cache->commit();
    }
}
