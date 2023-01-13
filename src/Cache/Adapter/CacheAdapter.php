<?php

namespace App\Cache\Adapter;

use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class CacheAdapter
{
    public function saveToCache(string $key, mixed $value): mixed
    {
        $adapter = new ApcuAdapter('key_prefix_');

        return $adapter->get($key, function (ItemInterface $item) use ($value) {
            dump("Setting {$value} in the cache");
            $item->set($value);
        });
    }
}