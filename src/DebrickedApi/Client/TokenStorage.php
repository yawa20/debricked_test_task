<?php

declare(strict_types=1);

namespace App\DebrickedApi\Client;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class TokenStorage
{
    private const TOKEN_KEY = 'debricked_jwt_token';
    private const TTL = 3600;

    public function __construct(private CacheInterface $memcachedPool)
    {
    }

    public function getToken(ApiClientInterface $apiClient): string
    {
        return $this->memcachedPool->get(self::TOKEN_KEY,
            function (ItemInterface $item) use ($apiClient) {
                $item->expiresAfter(self::TTL);
                return $apiClient->loginRefresh();
            }
        );
    }

    public function resetToken(ApiClientInterface $apiClient): string
    {
        $this->memcachedPool->delete(self::TOKEN_KEY);

        return $this->getToken($apiClient);
    }
}