<?php

namespace Laravie\Codex\Common;

use Laravie\Codex\Contracts\Endpoint as EndpointContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

trait HttpClient
{
    /**
     * Http Client instance.
     *
     * @var \Http\Client\Common\HttpMethodsClient
     */
    protected $http;

    /**
     * List of HTTP requests.
     *
     * @var array
     */
    protected $httpRequestQueries = [];

    /**
     * Send the HTTP request.
     *
     * @param  array<string, mixed>  $headers
     * @param  \Psr\Http\Message\StreamInterface|\Laravie\Codex\Common\Payload|array|null  $body
     */
    public function send(string $method, EndpointContract $uri, array $headers = [], $body = []): ResponseInterface
    {
        $method = strtoupper($method);

        if ($method === 'GET' && ! $body instanceof StreamInterface) {
            $uri->addQuery($body);
            $body = null;
        }

        [$headers, $body] = $this->prepareRequestPayloads($headers, $body);

        return $this->requestWith($method, $uri->get(), $headers, $body);
    }

    /**
     * Stream (multipart) the HTTP request.
     *
     * @param  array<string, mixed>  $headers
     */
    public function stream(string $method, EndpointContract $uri, array $headers, StreamInterface $stream): ResponseInterface
    {
        [$headers, $stream] = $this->prepareRequestPayloads($headers, $stream);

        return $this->requestWith(
            strtoupper($method), $uri->get(), $headers, $stream
        );
    }

    /**
     * Stream (multipart) the HTTP request.
     *
     * @param  array<string, mixed>  $headers
     * @param  \Psr\Http\Message\StreamInterface|\Laravie\Codex\Common\Payload|array|null  $body
     */
    protected function requestWith(string $method, UriInterface $uri, array $headers, $body): ResponseInterface
    {
        if (\in_array($method, ['HEAD', 'GET', 'TRACE'])) {
            $body = null;
        }

        $response = $this->http->send($method, $uri, $headers, $body);

        $this->httpRequestQueries[] = compact('method', 'uri', 'headers', 'body', 'response');

        return $response;
    }

    /**
     * Prepare request payloads.
     *
     * @param  array<string, mixed>  $headers
     * @param  \Psr\Http\Message\StreamInterface|\Laravie\Codex\Common\Payload|array|null  $body
     */
    protected function prepareRequestPayloads(array $headers = [], $body = []): array
    {
        $headers = $this->prepareRequestHeaders($headers);

        return [$headers, Payload::make($body)->get($headers)];
    }

    /**
     * Prepare request headers.
     *
     * @param  array<string, mixed>  $headers
     * @return array<string, mixed>
     */
    abstract protected function prepareRequestHeaders(array $headers = []): array;
}
