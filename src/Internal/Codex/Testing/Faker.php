<?php

namespace Laravie\Codex\Testing;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Utils;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Mockery as m;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Faker
{
    /**
     * HTTP methods client.
     *
     * @var \Http\Client\Common\HttpMethodsClient
     */
    protected $http;

    /**
     * Mock for "Http\Client\HttpClient".
     *
     * @var \Mockery\MockeryInterface
     */
    protected $client;

    /**
     * Mock for "Psr\Http\Message\ResponseInterface".
     *
     * @var \Mockery\MockeryInterface
     */
    protected $message;

    /**
     * Expected URL endpoint.
     *
     * @var string
     */
    protected $expectedRequestEndpoint;

    /**
     * Expected HTTP Request headers.
     *
     * @var array
     */
    protected $expectedRequestHeaders = [];

    /**
     * Expected HTTP Response status code.
     *
     * @var int|null
     */
    protected $expectedResponseStatusCode;

    /**
     * Expected HTTP Response reason phrase.
     *
     * @var string|null
     */
    protected $expectedResponseReasonPhrase;

    /**
     * Expected HTTP Response body.
     *
     * @var string|null
     */
    protected $expectedResponseBody;

    /**
     * Expected HTTP Response headers.
     *
     * @var array
     */
    protected $expectedResponseHeaders = [];

    /**
     * Construct a fake request.
     */
    public function __construct()
    {
        $this->client = m::mock(HttpClient::class);
        $this->message = m::mock(ResponseInterface::class);
        $factory = new HttpFactory;

        $this->http = new HttpMethodsClient(
            $this->client, $factory, $factory
        );
    }

    /**
     * Create a fake request.
     *
     * @return static
     */
    public static function create()
    {
        return new static;
    }

    /**
     * Set expected URL.
     *
     * @return $this
     */
    public function expectEndpointIs(string $endpoint)
    {
        $this->expectedRequestEndpoint = $endpoint;

        return $this;
    }

    /**
     * Make expected HTTP request.
     *
     * @param  \Mockery\Matcher\Type|array  $headers
     * @param  \Mockery\Matcher\Type|mixed  $body
     * @return $this
     */
    public function call(string $method, $headers = [], $body = '')
    {
        if ($method === 'GET') {
            $body = m::any();
        }

        $this->client->shouldReceive('sendRequest')
            ->with(m::on(function (RequestInterface $request) use ($method, $headers, $body): bool {
                Assert::assertSame($method, $request->getMethod());
                Assert::assertSame($this->expectedRequestEndpoint, (string) $request->getUri());

                $expectedHeaders = ! empty($this->expectedRequestHeaders) ? $this->expectedRequestHeaders : $headers;

                if (\is_array($expectedHeaders) && ! empty($expectedHeaders)) {
                    foreach ($expectedHeaders as $headerKey => $headerValue) {
                        Assert::assertTrue($request->hasHeader($headerKey));
                        Assert::assertSame(
                            \is_array($headerValue) ? $headerValue : ["{$headerValue}"],
                            $request->getHeader($headerKey)
                        );
                    }
                }

                if (! $body instanceof \Mockery\Matcher\MatcherAbstract && ! $body instanceof StreamInterface) {
                    Assert::assertSame((string) $body, (string) $request->getBody());
                }

                if ($body instanceof StreamInterface) {
                    Assert::assertSame((string) $body, (string) $request->getBody());
                }

                return true;
            }))
            ->andReturn($this->message());

        return $this;
    }

    /**
     * Make expected HTTP request.
     *
     * @param  \Mockery\Matcher\Type|array  $headers
     * @param  \Mockery\Matcher\Type|mixed  $body
     * @return $this
     */
    public function send(string $method, $headers = [], $body = '')
    {
        return $this->call($method, $headers, $body);
    }

    /**
     * Make expected HTTP JSON request.
     *
     * @param  \Mockery\Matcher\Type|array  $headers
     * @param  \Mockery\Matcher\Type|array|string  $body
     * @return $this
     */
    public function sendJson(string $method, $headers = [], $body = '')
    {
        if (\is_array($headers)) {
            $headers['Content-Type'] = 'application/json';
            $this->expectedRequestHeaders = $headers;
        }

        if (\is_array($body)) {
            $body = json_encode($body);
        }

        return $this->call($method, $headers, $body);
    }

    /**
     * Make expected HTTP JSON request.
     *
     * @param  \Mockery\Matcher\Type|array  $headers
     * @return $this
     */
    public function stream(string $method, $headers = [])
    {
        if (\is_array($headers)) {
            $this->expectedRequestHeaders = $headers;
        }

        return $this->call($method, m::type('Array'), m::type(StreamInterface::class));
    }

    /**
     * Request should response with.
     *
     * @return $this
     */
    public function shouldResponseWith(int $code = 200, string $body = '', array $headers = [])
    {
        $this->expectedResponseStatusCode = $code;
        $this->expectedResponseBody = $body;

        $this->message->shouldReceive('getStatusCode')->andReturn($code)
            ->shouldReceive('getBody')->andReturn(Utils::streamFor($body));

        $this->expectResponseHeaders($headers);

        return $this;
    }

    /**
     * Request should response with.
     *
     * @return $this
     */
    public function shouldResponseWithJson(int $code = 200, string $body = '', array $headers = [])
    {
        $headers['Content-Type'] = 'application/json';

        return $this->shouldResponseWith($code, $body, $headers);
    }

    /**
     * Response should have reason phrase as.
     *
     * @return $this
     */
    public function expectResponseHeaders(array $headers)
    {
        foreach ($headers as $headerKey => $headerValue) {
            if (! \is_string($headerKey)) {
                continue;
            }

            if (\is_array($headerValue)) {
                $this->expectedResponseHeaders[$headerKey] = array_merge(
                    $this->expectedResponseHeaders[$headerKey] ?? [], $headerValue
                );
            } else {
                $this->expectedResponseHeaders[$headerKey][] = "{$headerValue}";
            }
        }

        $this->message->shouldReceive('hasHeader')
            ->andReturnUsing(function ($key) {
                return \array_key_exists($key, $this->expectedResponseHeaders);
            });

        $this->message->shouldReceive('getHeader')
            ->andReturnUsing(function ($key) {
                return \array_key_exists($key, $this->expectedResponseHeaders)
                    ? $this->expectedResponseHeaders[$key]
                    : [];
            });

        $this->message->shouldReceive('getHeaderLine')
            ->andReturnUsing(function ($key) {
                return \array_key_exists($key, $this->expectedResponseHeaders)
                    ? implode(', ', $this->expectedResponseHeaders[$key])
                    : '';
            });

        return $this;
    }

    /**
     * Response should have reason phrase as.
     *
     * @return $this
     */
    public function expectReasonPhraseIs(string $reason)
    {
        $this->expectedResponseReasonPhrase = $reason;

        $this->message->shouldReceive('getReasonPhrase')->andReturn($reason);

        return $this;
    }

    /**
     * Response should have reason phrase as.
     *
     * @return $this
     */
    public function expectContentTypeIs(string $contentType)
    {
        return $this->expectResponseHeaders([
            'Content-Type' => $contentType,
        ]);
    }

    /**
     * Get HTTP mock.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    public function http()
    {
        return $this->http;
    }

    /**
     * Get message mock.
     *
     * @return \Mockery\MockeryInterface
     */
    public function message()
    {
        return $this->message;
    }
}
