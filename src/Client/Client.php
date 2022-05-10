<?php

declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Client;

use Http\Client\Exception\HttpException;
use Http\Client\Exception\TransferException as HttpTransferException;
use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Yproximite\Ekomi\Api\Exception\AuthenficationException;
use Yproximite\Ekomi\Api\Exception\InvalidResponseException;
use Yproximite\Ekomi\Api\Exception\RequestException;
use Yproximite\Ekomi\Api\Exception\TransferException;
use Yproximite\Ekomi\Api\Proxy\CacheProxy;

class Client
{
    const BASE_URL = 'https://csv.ekomiapps.de/api/3.0';

    const CACHE_KEY = 'yproximite.ekomi.cache_key';

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var MessageFactory|null
     */
    private $messageFactory;

    /**
     * @var CacheProxy
     */
    private $cacheProxy;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * Client constructor.
     */
    public function __construct(
        HttpClient $httpClient,
        string $clientId,
        string $secretKey,
        string $baseUrl = self::BASE_URL,
        MessageFactory $messageFactory = null,
        CacheItemPoolInterface $cache = null,
        string $cacheKey = self::CACHE_KEY
    ) {
        $this->httpClient     = $httpClient;
        $this->messageFactory = $messageFactory;
        $this->clientId       = $clientId;
        $this->secretKey      = $secretKey;
        $this->baseUrl        = $baseUrl;
        $this->cacheProxy     = new CacheProxy($cache);
        $this->cacheKey       = $cacheKey;
    }

    /**
     * Sends a request
     *
     * @param array|resource|string|StreamInterface|null $body
     *
     * @return mixed
     *
     * @throws TransferException
     * @throws InvalidResponseException
     * @throws AuthenficationException
     */
    public function sendRequest(string $method, string $path, $body = null, array $headers = [])
    {
        try {
            $request = $this->createRequest($method, $path, $body, $headers);
            $content = $this->doSendRequest($request);
        } catch (InvalidResponseException $e) {
            if ((401 === $e->getResponse()->getStatusCode() || 403 === $e->getResponse()->getStatusCode())
                && $this->cacheProxy->hasItem($this->cacheKey)
            ) {
                $this->resetApiToken();

                $request = $this->createRequest($method, $path, $body, $headers);
                $content = $this->doSendRequest($request);
            } else {
                throw $e;
            }
        }

        return $content;
    }

    /**
     * @param null $body
     */
    private function createRequest(
        string $method,
        string $path,
        $data = null,
        array $headers = [],
        bool $withAuthorization = true
    ): RequestInterface {
        $uri  = $this->baseUrl.'/'.$path;
        $body = null;

        if (in_array($method, $this->getQueryMethods())) {
            $query = is_array($data) ? http_build_query($data) : $data;

            if (is_string($query) && '' !== $query) {
                $uri .= '?'.$query;
            }
        } else {
            $body = is_array($data) ? json_encode($data) : $data;
        }

        $baseHeaders = ['Content-Type' => 'application/json'];

        if ($withAuthorization) {
            $baseHeaders['Authorization'] = $this->getAuthorizationHeader();
        }

        return $this->getMessageFactory()->createRequest($method, $uri, $headers + $baseHeaders, $body);
    }

    /**
     * @return mixed
     */
    private function doSendRequest(RequestInterface $request)
    {
        try {
            $response = $this->getHttpClient()->sendRequest($request);
        } catch (HttpTransferException $e) {
            throw new TransferException($e->getMessage(), $request, $e instanceof HttpException ? $e->getResponse() : null);
        }

        if ($response->getStatusCode() >= 400) {
            throw new InvalidResponseException('Bad response status code.', $request, $response);
        }

        $rawData = (string) $response->getBody();

        if (empty($rawData)) {
            return null;
        }

        $data = json_decode($rawData, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidResponseException('Could not decode the response.', $request, $response);
        }

        return $data;
    }

    private function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    private function getMessageFactory(): MessageFactory
    {
        if (null === $this->messageFactory) {
            $this->messageFactory = MessageFactoryDiscovery::find();
        }

        return $this->messageFactory;
    }

    /**
     * Returns all methods that uses query string to transfer a request data
     */
    private function getQueryMethods(): array
    {
        return ['GET', 'HEAD', 'DELETE'];
    }

    private function getApiToken(): string
    {
        $apiTokenItem = $this->cacheProxy->getItem($this->cacheKey);

        if ($apiToken = $apiTokenItem->get()) {
            return $apiToken;
        }

        $apiToken = $this->updateApiToken();
        $apiTokenItem->set($apiToken);

        $this->cacheProxy->save($apiTokenItem);

        return $apiToken;
    }

    private function updateApiToken(): string
    {
        $params  = ['username' => $this->clientId, 'password' => $this->secretKey];
        $request = $this->createRequest('POST', 'security/login', $params, [], false);

        try {
            $data = $this->doSendRequest($request);
        } catch (RequestException $e) {
            throw new AuthenficationException('Could not request a token.');
        }

        if (!is_array($data) || !array_key_exists('access_token', $data)) {
            throw new AuthenficationException('Could not retreive a token.');
        }

        return (string) $data['access_token'];
    }

    private function resetApiToken()
    {
        $this->cacheProxy->deleteItem($this->cacheKey);
    }

    private function getAuthorizationHeader(): string
    {
        return sprintf('ekomi %s', $this->getApiToken());
    }
}
