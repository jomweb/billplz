<?php

namespace Laravie\Codex\Testing;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Utils;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\HttpClient;
use Mockery as m;
use Mockery\Expectation;
use Mockery\MockInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Faker
{
    /**
     * HTTP methods client.
     */
    protected HttpMethodsClient $http;

    /**
     * Mock for "Http\Client\HttpClient".
     */
    protected MockInterface $client;

    /**
     * Mock for "Psr\Http\Message\ResponseInterface".
     */
    protected MockInterface $message;

    /**
     * Expected URL endpoint.
     */
    protected ?string $expectedRequestEndpoint;

    /**
     * Expected HTTP Request headers.
     */
    protected array $expectedRequestHeaders = [];

    /**
     * Expected HTTP Response status code.
     */
    protected ?int $expectedResponseStatusCode;

    /**
     * Expected HTTP Response reason phrase.
     */
    protected ?string $expectedResponseReasonPhrase;

    /**
     * Expected HTTP Response body.
     */
    protected ?string $expectedResponseBody;

    /**
     * Expected HTTP Response headers.
     */
    protected array $expectedResponseHeaders = [];

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
    public static function create(): self
    {
        return new static;
    }

    /**
     * Set expected URL.
     *
     * @return $this
     */
    public function expectEndpointIs(string $endpoint): self
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
    public function call(string $method, mixed $headers = [], mixed $body = ''): self
    {
        if ($method === 'GET') {
            $body = m::any();
        }

        /** @var Expectation $expectation */
        $expectation = $this->client->shouldReceive('sendRequest');

        $expectation
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
    public function send(string $method, mixed $headers = [], mixed $body = ''): self
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
    public function sendJson(string $method, mixed $headers = [], mixed $body = ''): self
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
    public function stream(string $method, mixed $headers = []): self
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
    public function shouldResponseWith(int $code = 200, string $body = '', array $headers = []): self
    {
        $this->expectedResponseStatusCode = $code;
        $this->expectedResponseBody = $body;

        /** @var Expectation $expectStatusCode */
        $expectStatusCode = $this->message->shouldReceive('getStatusCode');
        $expectStatusCode->andReturn($code);

        /** @var Expectation $expectBody */
        $expectBody = $this->message->shouldReceive('getBody');
        $expectBody->andReturn(Utils::streamFor($body));

        $this->expectResponseHeaders($headers);

        return $this;
    }

    /**
     * Request should response with.
     *
     * @return $this
     */
    public function shouldResponseWithJson(int $code = 200, string $body = '', array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json';

        return $this->shouldResponseWith($code, $body, $headers);
    }

    /**
     * Response should have reason phrase as.
     *
     * @return $this
     */
    public function expectResponseHeaders(array $headers): self
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

        /** @var Expectation $expectHasHeader */
        $expectHasHeader = $this->message->shouldReceive('hasHeader');

        $expectHasHeader
            ->andReturnUsing(function (string $key): bool {
                return \array_key_exists($key, $this->expectedResponseHeaders);
            });

        /** @var Expectation $expectGetHeader */
        $expectGetHeader = $this->message->shouldReceive('getHeader');

        $expectGetHeader
            ->andReturnUsing(function (string $key): array {
                return \array_key_exists($key, $this->expectedResponseHeaders)
                    ? $this->expectedResponseHeaders[$key]
                    : [];
            });

        /** @var Expectation $expectGetHeaderLine */
        $expectGetHeaderLine = $this->message->shouldReceive('getHeaderLine');

        $expectGetHeaderLine
            ->andReturnUsing(function (string $key): string {
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
    public function expectReasonPhraseIs(string $reason): self
    {
        $this->expectedResponseReasonPhrase = $reason;

        /** @var Expectation $expectReasonPhrase */
        $expectReasonPhrase = $this->message->shouldReceive('getReasonPhrase');
        $expectReasonPhrase->andReturn($reason);

        return $this;
    }

    /**
     * Response should have reason phrase as.
     *
     * @return $this
     */
    public function expectContentTypeIs(string $contentType): self
    {
        return $this->expectResponseHeaders([
            'Content-Type' => $contentType,
        ]);
    }

    /**
     * Get HTTP mock.
     */
    public function http(): HttpMethodsClient
    {
        return $this->http;
    }

    /**
     * Get message mock.
     */
    public function message(): MockInterface
    {
        return $this->message;
    }
}
