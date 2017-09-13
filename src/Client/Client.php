<?php
declare(strict_types=1);

namespace Yproximite\Ekomi\Api\Client;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Http\Client\Exception\HttpException;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Client\Exception\TransferException as HttpTransferException;

use Yproximite\Ekomi\Api\Exception\RequestException;
use Yproximite\Ekomi\Api\Exception\TransferException;
use Yproximite\Ekomi\Api\Exception\AuthenficationException;
use Yproximite\Ekomi\Api\Exception\InvalidResponseException;
use Yproximite\Ekomi\Api\Proxy\CacheProxy;

/**
 * Class Client
 */
class Client
{
    const BASE_URL = 'https://csv.ekomi.com/api/3.0';

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
     *
     * @param HttpClient          $httpClient
     * @param string              $clientId
     * @param string              $secretKey
     * @param string              $baseUrl
     * @param MessageFactory|null $messageFactory
     */
    public function __construct(
        HttpClient $httpClient,
        string $clientId,
        string $secretKey,
        string $baseUrl = self::BASE_URL,
        MessageFactory $messageFactory = null,
        CacheItemPoolInterface $cache = null,
        string $cacheKey = 'yproximite.ekomi.cache_key'
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
     * @param string                                     $method
     * @param string                                     $path
     * @param array|resource|string|StreamInterface|null $body
     * @param array                                      $headers
     *
     * @return mixed
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
            if (($e->getResponse()->getStatusCode() === 401 || $e->getResponse()->getStatusCode() === 403)
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
     * @param string $method
     * @param string $path
     * @param null   $body
     * @param array  $headers
     * @param bool   $withAuthorization
     *
     * @return RequestInterface
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

            if (is_string($query) && $query !== '') {
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
     * @param RequestInterface $request
     *
     * @return mixed
     */
    private function doSendRequest(RequestInterface $request)
    {
        try {
            $response = $this->getHttpClient()->sendRequest($request);
        } catch (HttpTransferException $e) {
            throw new TransferException(
                $e->getMessage(),
                $request,
                $e instanceof HttpException ? $e->getResponse() : null
            );
        }

        if ($response->getStatusCode() >= 400) {
            throw new InvalidResponseException('Bad response status code.', $request, $response);
        }

        $rawData = (string) $response->getBody();

        if (empty($rawData)) {
            return null;
        }

        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidResponseException('Could not decode the response.', $request, $response);
        }

        return $data;
    }

    /**
     * @return HttpClient
     */
    private function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    /**
     * @return MessageFactory
     */
    private function getMessageFactory(): MessageFactory
    {
        if ($this->messageFactory === null) {
            $this->messageFactory = MessageFactoryDiscovery::find();
        }

        return $this->messageFactory;
    }

    /**
     * Returns all methods that uses query string to transfer a request data
     *
     * @return array
     */
    private function getQueryMethods(): array
    {
        return ['GET', 'HEAD', 'DELETE'];
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    private function getAuthorizationHeader(): string
    {
        return sprintf('ekomi %s', $this->getApiToken());
    }
}
