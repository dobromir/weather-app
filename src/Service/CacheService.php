<?php

namespace App\Service;

use Predis\ClientInterface;

class CacheService
{
    /**
     * Redis client.
     *
     * @var ClientInterface $redis
     */
    private ClientInterface $redis;

    /**
     * CacheService constructor.
     *
     * @param ClientInterface $redis
     */
    public function __construct(ClientInterface $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Set a key-value pair in the Redis cache. The key will expire after the specified time-to-live (TTL).
     *
     * @param string $key
     * @param string $value
     * @param int $ttl
     */
    public function setValue(string $key, string $value, int $ttl = 3600): void
    {
        $this->redis->set($key, $value);
        $this->redis->expire($key, $ttl);
    }

    /**
     * Get the value of a key from the Redis cache.
     *
     * @param string $key
     * @return string|null
     */
    public function getValue(string $key): ?string
    {
        return $this->redis->get($key);
    }
}
