<?php

declare(strict_types=1);

namespace spec\Yproximite\Ekomi\Api\Client;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Yproximite\Ekomi\Api\Client\Client;
use Yproximite\Ekomi\Api\Exception\AuthenficationException;
use Yproximite\Ekomi\Api\Exception\InvalidResponseException;

class ClientSpec extends ObjectBehavior
{
    const BASE_URL  = 'http://api.host';
    const CACHE_KEY = 'cacheKey';

    public function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    public function let(
        HttpClientInterface $client,
        ResponseInterface $responseLogin,
    ) {
        $requestLoginUri = sprintf('%s/security/login', self::BASE_URL);

        $responseLogin->getStatusCode()->willReturn(200);
        $responseLogin->toArray()->willReturn(['access_token' => 'access_token']);

        $client->request('POST', $requestLoginUri, [
            'headers' => [
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'username' => 'username',
                'password' => 'password',
            ],
        ])->willReturn($responseLogin);

        $this->beConstructedWith(
            $client,
            new TagAwareAdapter(new NullAdapter()),
            'username',
            'password',
            self::BASE_URL,
            static::CACHE_KEY
        );
    }

    public function it_should_send_get_request(HttpClientInterface $client, ResponseInterface $response)
    {
        $requestUri = sprintf('%s/example', self::BASE_URL);

        $response->getStatusCode()->willReturn(200);
        $response->toArray()->willReturn([]);

        $client->request('GET', $requestUri, [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'ekomi access_token',
            ],
            'query'   => [
                'query' => 'test',
            ],
        ])
            ->willReturn($response)
            ->shouldBeCalled();

        $this->sendRequest('GET', 'example', ['query' => 'test']);
    }

    public function it_should_send_post_request(HttpClientInterface $client, ResponseInterface $response)
    {
        $requestUri = sprintf('%s/example', self::BASE_URL);

        $response->getStatusCode()->willReturn(200);
        $response->toArray()->willReturn([]);

        $client->request('POST', $requestUri, [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'ekomi access_token',
            ],
            'query'   => [
                'query' => 'test',
            ],
        ])
            ->willReturn($response)
            ->shouldBeCalled();


        $this->sendRequest('POST', 'example', ['query' => 'test']);
    }


    public function it_should_throw_invalid_response_exception_on_bad_status_code(HttpClientInterface $client, ResponseInterface $response)
    {
        $requestUri = sprintf('%s/example', self::BASE_URL);

        $response->getStatusCode()->willReturn(400);
        $response->toArray()->willReturn([]);

        $client->request('GET', $requestUri, [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'ekomi access_token',
            ],
            'query'   => [],
        ])
            ->willReturn($response)
            ->shouldBeCalled();

        $this->shouldThrow(InvalidResponseException::class)->during('sendRequest', ['GET', 'example']);
    }

    public function it_should_throw_invalid_response_exception_on_broken_response_body(HttpClientInterface $client, ResponseInterface $response)
    {
        $requestUri = sprintf('%s/example', self::BASE_URL);

        $response->getStatusCode()->willReturn(200);
        $response->toArray()->willThrow(\JsonException::class);

        $client->request('GET', $requestUri, [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'ekomi access_token',
            ],
            'query'   => [],
        ])
            ->willReturn($response)
            ->shouldBeCalled();

        $this->shouldThrow(InvalidResponseException::class)->during('sendRequest', ['GET', 'example']);
    }

    public function it_should_throw_authenfication_exception_on_http_exception(HttpClientInterface $client, ResponseInterface $responseLogin)
    {
        $requestLoginUri = sprintf('%s/security/login', self::BASE_URL);

        $responseLogin->getStatusCode()->willReturn(200);
        $responseLogin->toArray()->willReturn([]);

        $client->request('POST', $requestLoginUri, [
            'headers' => [
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'username' => 'username',
                'password' => 'password',
            ],
        ])
            ->willReturn($responseLogin)
            ->shouldBeCalled();

        $this->shouldThrow(AuthenficationException::class)->during('sendRequest', ['GET', 'example']);
    }
}
