<?php

declare(strict_types=1);

namespace App\DebrickedApi\Client;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ApiClientInterface
{
    public function loginRefresh(): string;

    /**
     * Actually, will be better to replace external ResponseInterface to some internal response
     */
    public function post(string $route, iterable $body = [], array $headers = []): ResponseInterface;

    public function get(string $route, iterable $params = [], array $headers = []): ResponseInterface;
}