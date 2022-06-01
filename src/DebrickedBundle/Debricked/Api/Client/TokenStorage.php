<?php

declare(strict_types=1);

namespace DebrickedBundle\Debricked\Api\Client;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class TokenStorage
{
    private const TOKEN_KEY = 'debricked_jwt_token';
    private const TTL = 3600;

    public function __construct(private CacheInterface $memcachedPool)
    {
    }

    public function getToken(callable $callable): string
    {
        return $this->memcachedPool->get(self::TOKEN_KEY,
            function (ItemInterface $item) use ($callable) {
                $item->expiresAfter(self::TTL);
                return $callable();
            }
        );
    }

    public function resetToken(callable $callable): string
    {
        try {
            $this->memcachedPool->delete(self::TOKEN_KEY);
        } catch (InvalidArgumentException $e) {
            //All OK, do nothing
        }

        return $this->getToken($callable);
    }
}