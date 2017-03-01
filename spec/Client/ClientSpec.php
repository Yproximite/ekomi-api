<?php

namespace spec\Yproximite\Ekomi\Api\Client;

use Http\Client\HttpClient;
use PhpSpec\ObjectBehavior;
use Http\Message\MessageFactory;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Http\Client\Exception\HttpException;
use Http\Discovery\MessageFactoryDiscovery;

use Yproximite\Ekomi\Api\Client\Client;
use Yproximite\Ekomi\Api\Exception\TransferException;
use Yproximite\Ekomi\Api\Exception\AuthenficationException;
use Yproximite\Ekomi\Api\Exception\InvalidResponseException;

class ClientSpec extends ObjectBehavior
{
    const BASE_URL = 'http://api.host';

    function it_is_initializable()
    {
        $this->shouldHaveType(Client::class);
    }

    function let(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        RequestInterface $tokenRequest,
        ResponseInterface $tokenResponse,
        StreamInterface $tokenStream
    ) {
        $tokenHeaders     = ['Content-Type' => 'application/json'];
        $tokenRequestUri  = sprintf('%s/security/login', self::BASE_URL);
        $tokenRawRequest  = json_encode(['username' => 'abcd', 'password' => 'xxxx']);
        $tokenRawResponse = json_encode(['access_token' => 'efgh']);

        $messageFactory->createRequest('POST', $tokenRequestUri, $tokenHeaders, $tokenRawRequest)->willReturn($tokenRequest);
        $httpClient->sendRequest($tokenRequest)->willReturn($tokenResponse);
        $tokenResponse->getStatusCode()->willReturn(200);
        $tokenResponse->getBody()->willReturn($tokenStream);
        $tokenStream->__toString()->willReturn($tokenRawResponse);

        $this->beConstructedWith($httpClient, 'abcd', 'xxxx', self::BASE_URL, $messageFactory);
    }

    function it_should_send_get_request(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $headers     = ['Content-Type' => 'application/json', 'Authorization' => 'ekomi efgh'];
        $rawQuery    = http_build_query(['query' => 'test']);
        $requestUri  = sprintf('%s/example?%s', self::BASE_URL, $rawQuery);
        $rawResponse = json_encode(['foo' => 'bar']);

        $messageFactory->createRequest('GET', $requestUri, $headers, null)->willReturn($request);
        $httpClient->sendRequest($request)->willReturn($response);
        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn($rawResponse);

        $httpClient->sendRequest($request)->shouldBeCalled();

        $this->sendRequest('GET', 'example', ['query' => 'test']);
    }

    function it_should_send_post_request(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $headers     = ['Content-Type' => 'application/json', 'Authorization' => 'ekomi efgh'];
        $requestUri  = sprintf('%s/example', self::BASE_URL);
        $rawRequest  = json_encode(['query' => 'test']);
        $rawResponse = json_encode(['foo' => 'bar']);

        $messageFactory->createRequest('POST', $requestUri, $headers, $rawRequest)->willReturn($request);
        $httpClient->sendRequest($request)->willReturn($response);
        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn($rawResponse);

        $httpClient->sendRequest($request)->shouldBeCalled();

        $this->sendRequest('POST', 'example', ['query' => 'test']);
    }

    function it_should_ask_for_api_token_once(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        RequestInterface $firstRequest,
        ResponseInterface $firstResponse,
        StreamInterface $firstStream,
        RequestInterface $secondRequest,
        ResponseInterface $secondResponse,
        StreamInterface $secondStream,
        RequestInterface $tokenRequest
    ) {
        // first request
        $firstHeaders     = ['Content-Type' => 'application/json', 'Authorization' => 'ekomi efgh'];
        $firstRequestUri  = sprintf('%s/first', self::BASE_URL);
        $firstRawResponse = json_encode(['foo' => 'bar']);

        $messageFactory->createRequest('GET', $firstRequestUri, $firstHeaders, null)->willReturn($firstRequest);
        $httpClient->sendRequest($firstRequest)->willReturn($firstResponse);
        $firstResponse->getStatusCode()->willReturn(200);
        $firstResponse->getBody()->willReturn($firstStream);
        $firstStream->__toString()->willReturn($firstRawResponse);

        $httpClient->sendRequest($firstRequest)->shouldBeCalled();

        $this->sendRequest('GET', 'first');

        // second request
        $secondHeaders     = ['Content-Type' => 'application/json', 'Authorization' => 'ekomi efgh'];
        $secondRequestUri  = sprintf('%s/second', self::BASE_URL);
        $secondRawResponse = json_encode(['foo' => 'bar']);

        $messageFactory->createRequest('GET', $secondRequestUri, $secondHeaders, null)->willReturn($secondRequest);
        $httpClient->sendRequest($secondRequest)->willReturn($secondResponse);
        $secondResponse->getStatusCode()->willReturn(200);
        $secondResponse->getBody()->willReturn($secondStream);
        $secondStream->__toString()->willReturn($secondRawResponse);

        $httpClient->sendRequest($secondRequest)->shouldBeCalled();

        $this->sendRequest('GET', 'second');

        $httpClient->sendRequest($tokenRequest)->shouldHaveBeenCalledTimes(1);
    }

    function it_should_renew_the_token_and_resend_the_request(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        RequestInterface $firstRequest,
        ResponseInterface $firstResponse,
        StreamInterface $firstStream,
        RequestInterface $secondRequest,
        ResponseInterface $secondResponse,
        StreamInterface $secondStream,
        RequestInterface $tokenRequest
    ) {
        // first request
        $firstHeaders     = ['Content-Type' => 'application/json', 'Authorization' => 'ekomi efgh'];
        $firstRequestUri  = sprintf('%s/first', self::BASE_URL);
        $firstRawResponse = json_encode(['foo' => 'bar']);

        $messageFactory->createRequest('GET', $firstRequestUri, $firstHeaders, null)->willReturn($firstRequest);
        $httpClient->sendRequest($firstRequest)->willReturn($firstResponse);
        $firstResponse->getStatusCode()->willReturn(200);
        $firstResponse->getBody()->willReturn($firstStream);
        $firstStream->__toString()->willReturn($firstRawResponse);

        $httpClient->sendRequest($firstRequest)->shouldBeCalled();

        $this->sendRequest('GET', 'first');

        // second request
        $secondHeaders     = ['Content-Type' => 'application/json', 'Authorization' => 'ekomi efgh'];
        $secondRequestUri  = sprintf('%s/second', self::BASE_URL);
        $secondRawResponse = json_encode(['foo' => 'bar']);

        $messageFactory->createRequest('GET', $secondRequestUri, $secondHeaders, null)->willReturn($secondRequest);
        $secondResponse->getStatusCode()->willReturn(200);
        $secondResponse->getBody()->willReturn($secondStream);
        $secondStream->__toString()->willReturn($secondRawResponse);

        $secondRequestCounter = 0;

        $httpClient->sendRequest($secondRequest)->will(function () use ($secondRequestCounter, $secondResponse) {
            $secondRequestCounter ++;

            if ($secondRequestCounter === 1) {
                return MessageFactoryDiscovery::find()->createResponse(401);
            }

            return $secondResponse;
        });

        $httpClient->sendRequest($secondRequest)->shouldBeCalledTimes(2);

        $this->shouldThrow(InvalidResponseException::class)->during('sendRequest', ['GET', 'second']);

        $httpClient->sendRequest($tokenRequest)->shouldHaveBeenCalledTimes(2);
    }

    function it_should_not_renew_the_token(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $stream,
        RequestInterface $tokenRequest
    ) {
        $headers     = ['Content-Type' => 'application/json', 'Authorization' => 'ekomi efgh'];
        $requestUri  = sprintf('%s/example', self::BASE_URL);
        $rawResponse = json_encode(['foo' => 'bar']);

        $messageFactory->createRequest('GET', $requestUri, $headers, null)->willReturn($request);
        $httpClient->sendRequest($request)->willReturn($response);
        $response->getStatusCode()->willReturn(401);
        $stream->__toString()->willReturn($rawResponse);

        $httpClient->sendRequest($request)->shouldBeCalled();

        $this->shouldThrow(InvalidResponseException::class)->during('sendRequest', ['GET', 'example']);

        $httpClient->sendRequest($tokenRequest)->shouldHaveBeenCalledTimes(1);
    }

    function it_should_throw_transfer_exception_on_http_exception(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        RequestInterface $request
    ) {
        $headers     = ['Content-Type' => 'application/json', 'Authorization' => 'ekomi efgh'];
        $requestUri  = sprintf('%s/example', self::BASE_URL);

        $errorResponse = MessageFactoryDiscovery::find()->createResponse(500);
        $httpException = HttpException::create($request->getWrappedObject(), $errorResponse);

        $messageFactory->createRequest('GET', $requestUri, $headers, null)->willReturn($request);
        $httpClient->sendRequest($request)->willThrow($httpException);

        $this->shouldThrow(TransferException::class)->during('sendRequest', ['GET', 'example']);
    }

    function it_should_throw_invalid_response_exception_on_bad_status_code(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $headers     = ['Content-Type' => 'application/json', 'Authorization' => 'ekomi efgh'];
        $requestUri  = sprintf('%s/example', self::BASE_URL);

        $messageFactory->createRequest('GET', $requestUri, $headers, null)->willReturn($request);
        $httpClient->sendRequest($request)->willReturn($response);
        $response->getStatusCode()->willReturn(400);

        $this->shouldThrow(InvalidResponseException::class)->during('sendRequest', ['GET', 'example']);
    }

    function it_should_throw_invalid_response_exception_on_broken_response_body(
        HttpClient $httpClient,
        MessageFactory $messageFactory,
        RequestInterface $request,
        ResponseInterface $response,
        StreamInterface $stream
    ) {
        $headers     = ['Content-Type' => 'application/json', 'Authorization' => 'ekomi efgh'];
        $requestUri  = sprintf('%s/example', self::BASE_URL);

        $messageFactory->createRequest('GET', $requestUri, $headers, null)->willReturn($request);
        $httpClient->sendRequest($request)->willReturn($response);
        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($stream);
        $stream->__toString()->willReturn('it is not a json');

        $this->shouldThrow(InvalidResponseException::class)->during('sendRequest', ['GET', 'example']);
    }

    function it_should_throw_authenfication_exception_on_http_exception(
        HttpClient $httpClient,
        RequestInterface $tokenRequest
    ) {
        $errorResponse = MessageFactoryDiscovery::find()->createResponse(500);
        $httpException = HttpException::create($tokenRequest->getWrappedObject(), $errorResponse);

        $httpClient->sendRequest($tokenRequest)->willThrow($httpException);

        $this->shouldThrow(AuthenficationException::class)->during('sendRequest', ['GET', 'example']);
    }

    function it_should_throw_authenfication_exception_on_bad_status_code(ResponseInterface $tokenResponse)
    {
        $tokenResponse->getStatusCode()->willReturn(400);

        $this->shouldThrow(AuthenficationException::class)->during('sendRequest', ['GET', 'example']);
    }

    function it_should_throw_authenfication_exception_on_broken_response_body(StreamInterface $tokenStream)
    {
        $tokenStream->__toString()->willReturn('it is not a json');

        $this->shouldThrow(AuthenficationException::class)->during('sendRequest', ['GET', 'example']);
    }

    function it_should_throw_authenfication_exception_on_bad_response_body(StreamInterface $tokenStream)
    {
        $rawResponse = json_encode(['it' => 'is not a token']);

        $tokenStream->__toString()->willReturn($rawResponse);

        $this->shouldThrow(AuthenficationException::class)->during('sendRequest', ['GET', 'example']);
    }
}
