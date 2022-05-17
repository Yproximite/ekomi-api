<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Client;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Yproximite\Ekomi\Api\Exception\AuthenficationException;
use Yproximite\Ekomi\Api\Exception\InvalidResponseException;

class Client
{
    public const BASE_URL = 'https://csv.ekomiapps.de/api/3.0';

    public const CACHE_KEY = 'yproximite.ekomi.cache_key';

    public function __construct(
        private HttpClientInterface $client,
        private TagAwareCacheInterface $cache,
        private string $clientId,
        private string $secretKey,
        private string $baseUrl = self::BASE_URL,
        private string $cacheKey = self::CACHE_KEY
    ) {
    }

    /**
     * @param array<string, mixed> $body
     *
     * @throws InvalidResponseException
     * @throws AuthenficationException
     */
    public function sendRequest(string $method, string $path, array $body = [], array $headers = [])
    {
        $headers = [
            ...$headers,
            'Content-Type'  => 'application/json',
            'Authorization' => $this->getAuthorizationHeader(),
        ];

        try {
            $response = $this->client->request($method, sprintf('%s/%s', $this->baseUrl, $path), [
                'headers' => $headers,
                'query'   => $body,
            ]);

            if ($response->getStatusCode() >= 400) {
                throw new InvalidResponseException('Bad response status code.', $response);
            }

            $content = $response->toArray();
        } catch (InvalidResponseException $e) {
            if ((401 === $e->getResponse()->getStatusCode() || 403 === $e->getResponse()->getStatusCode())
                && $this->cache->hasItem($this->cacheKey)
            ) {
                $this->resetApiToken();

                $response = $this->client->request($method, sprintf('%s/%s', $this->baseUrl, $path), [
                    'headers' => $headers,
                    'query'   => $body,
                ]);

                if ($response->getStatusCode() >= 400) {
                    throw new InvalidResponseException('Bad response status code.', $response);
                }

                $content = $response->toArray();
            } else {
                throw $e;
            }
        }

        return $content;
    }

    private function getApiToken(): string
    {
        return $this->cache->get($this->cacheKey, function (ItemInterface $item) {
            $item->expiresAfter(60 * 60);

            return $this->updateApiToken();
        });
    }

    private function updateApiToken(): string
    {
        $params = ['username' => $this->clientId, 'password' => $this->secretKey];

        try {
            $response = $this->client->request('POST', sprintf('%s/%s', $this->baseUrl, 'security/login'), [
                'query' => $params,
            ]);

            if ($response->getStatusCode() >= 400) {
                throw new InvalidResponseException('Bad response status code.', $response);
            }

            $data = $response->toArray();
        } catch (\Exception) {
            throw new AuthenficationException('Could not request a token.');
        }

        if (!\array_key_exists('access_token', $data)) {
            throw new AuthenficationException('Could not retrieve a token.');
        }

        return $data['access_token'];
    }

    private function resetApiToken(): void
    {
        $this->cache->delete($this->cacheKey);
    }

    private function getAuthorizationHeader(): string
    {
        return sprintf('ekomi %s', $this->getApiToken());
    }
}
