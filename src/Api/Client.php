<?php

namespace Yproximite\Ekomi\Api;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Http\Discovery\MessageFactoryDiscovery;

use Yproximite\Ekomi\Api\Request\RequestInterface;
use Yproximite\Ekomi\Api\Exception\NoResultException;

/**
 * Class Client
 */
class Client
{
    /**
     * @var string
     */
    private $baseUrl = 'https://csv.ekomi.com/api/1.0/';

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
     * Client constructor.
     *
     * @param HttpClient          $httpClient
     * @param MessageFactory|null $messageFactory
     * @param string              $clientId
     * @param string              $secretKey
     */
    public function __construct(HttpClient $httpClient, $clientId, $secretKey, MessageFactory $messageFactory = null)
    {
        $this->httpClient     = $httpClient;
        $this->messageFactory = $messageFactory;
        $this->clientId       = $clientId;
        $this->secretKey      = $secretKey;
    }

    /**
     * @param RequestInterface $apiRequest
     *
     * @return mixed
     * @throws NoResultException
     */
    public function sendRequest(RequestInterface $apiRequest)
    {
        $method = $apiRequest->getMethod();
        $uri    = $this->baseUrl.$apiRequest->getPath();
        $query  = http_build_query($apiRequest->getQuery());
        $body   = null;

        if (in_array($method, ['GET', 'HEAD', 'DELETE'])) {
            $uri .= '?'.$query;
        } else {
            $body = $query;
        }

        $request = $this->getMessageFactory()
            ->createRequest($method, $uri, [], $body)
            ->withHeader('Authorization', $this->getAuthorizationHeader())
        ;

        $content = (string) $this->getHttpClient()->sendRequest($request)->getBody();

        if (empty($content)) {
            throw new NoResultException(
                sprintf('Could not execute query "%s %s".', $method, $uri)
            );
        }

        $data = json_decode($content, true);

        if (!isset($data)) {
            throw new NoResultException(
                sprintf('Could not execute query "%s %s".', $method, $uri)
            );
        }

        return $apiRequest->getResponseNormalizer()->normalize($data);
    }

    /**
     * @return HttpClient
     */
    private function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @return MessageFactory
     */
    private function getMessageFactory()
    {
        if ($this->messageFactory === null) {
            $this->messageFactory = MessageFactoryDiscovery::find();
        }

        return $this->messageFactory;
    }

    /**
     * @return string
     */
    private function getAuthorizationHeader()
    {
        return sprintf('ekomi %s|%s', $this->clientId, $this->secretKey);
    }
}
