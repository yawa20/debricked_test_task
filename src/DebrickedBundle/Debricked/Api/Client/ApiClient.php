<?php

declare(strict_types=1);

namespace DebrickedBundle\Debricked\Api\Client;

use DebrickedBundle\Debricked\Api\Exception\InvalidApiTokenException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiClient implements ApiClientInterface
{
    const BASE_ULR = 'https://debricked.com/api';
    const API_VER = '1.0';
    const METHOD__POST = 'POST';
    const METHOD__GET = 'GET';

    public function __construct(
        private HttpClientInterface $client,
        private TokenStorage $tokenStorage,
        private string $token
    ) {
    }

    /**
     * @throws InvalidApiTokenException
     * @throws TransportExceptionInterface
     */
    public function post(string $route, iterable $body = [], array $headers = []): ResponseInterface
    {
        return $this->authenticatedRequest(
            method: self::METHOD__POST,
            route: $route,
            body: $body,
            headers: $headers
        );
    }

    /**
     * @throws InvalidApiTokenException
     * @throws TransportExceptionInterface
     */
    private function authenticatedRequest(
        string $method,
        string $route,
        iterable $body = [],
        array $headers = [],
    ): ResponseInterface {
        $route = '/'.self::API_VER.'/'.ltrim($route, '/');
        $headers = array_merge($headers, [
            "Authorization" => "Bearer ".$this->tokenStorage->getToken([$this, 'loginRefresh']),
        ]);

        $response = $this->directQuery($method, $route, $body, $headers);
        if (401 === $response->getStatusCode()) {
            $this->tokenStorage->resetToken([$this, 'loginRefresh']);

            $response = $this->directQuery($method, $route, $headers, $body);
        }

        if (401 === $response->getStatusCode()) { //token still not valid
            throw new InvalidApiTokenException();
        }

        return $response;
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function directQuery(
        string $method,
        string $route,
        iterable $body = [],
        array $headers = []
    ): ResponseInterface {
        return $this->client->request(
            $method,
            self::BASE_ULR.$route,
            [
                'headers' => $headers,
                'body' => $body,
            ]
        );
    }

    /**
     * @throws InvalidApiTokenException
     * @throws TransportExceptionInterface
     */
    public function get(string $route, iterable $params = [], array $headers = []): ResponseInterface
    {
        $query = (empty($params)) ? '' : '?'.http_build_query($params);

        return $this->authenticatedRequest(
            method: self::METHOD__GET,
            route: $route.$query,
            headers: $headers,
        );
    }

    /**
     * @return string
     * @throws InvalidApiTokenException
     * @throws HttpException
     */
    public function loginRefresh(): string
    {
        if (null === $this->token) {
            throw new InvalidApiTokenException();
        }

        $response = $this->directQuery(
            method: 'POST',
            route: '/login_refresh',
            body: [
                'refresh_token' => $this->token,
            ]
        );

        if (200 !== $response->getStatusCode()) {
            throw new InvalidApiTokenException();
        }

        return json_decode($response->getContent())->token;
    }
}