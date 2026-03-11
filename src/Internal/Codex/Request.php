<?php

namespace Laravie\Codex;

use Laravie\Codex\Common\Payload;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @property Client $client
 */
abstract class Request extends Common\Request
{
    use Support\Responsable,
        Support\Versioning;

    /**
     * Version namespace.
     */
    protected string $version;

    /**
     * Automatically validate response.
     */
    protected bool $validateResponseAutomatically = true;

    /**
     * Construct a new Request.
     */
    public function __construct()
    {
        if (method_exists($this, 'sanitizeWith') && $this instanceof Contracts\Filterable) {
            $this->setFilterable($this->sanitizeWith());
        }
    }

    /**
     * Send API request.
     *
     * @param  array<string, mixed>  $headers
     * @param  StreamInterface|Payload|array|null  $body
     */
    protected function send(
        string $method,
        Contracts\Endpoint|string $path,
        array $headers = [],
        mixed $body = []
    ): Contracts\Response {
        if ($this instanceof Contracts\Filterable) {
            /** @var StreamInterface|Payload|array|null $body */
            $body = $this->filterRequest($body);
        }

        $endpoint = $path instanceof Contracts\Endpoint
            ? $this->getApiEndpoint($path->getPath())->addQuery($path->getQuery())
            : $this->getApiEndpoint($path);

        $message = $this->responseWith(
            $this->client->send($method, $endpoint, $headers, $body)
        );

        return $this->interactsWithResponse($message);
    }

    /**
     * Resolve the responder class.
     */
    protected function responseWith(ResponseInterface $message): Contracts\Response
    {
        return new Response($message);
    }

    /**
     * Get API Header.
     *
     * @return array<string, mixed>
     */
    protected function getApiHeaders(): array
    {
        return [];
    }

    /**
     * Get API Body.
     */
    protected function getApiBody(): array
    {
        return [];
    }

    /**
     * Merge API Headers.
     *
     * @param  array<string, mixed>  $headers
     * @return array<string, mixed>
     */
    final protected function mergeApiHeaders(array $headers = []): array
    {
        return array_merge($this->getApiHeaders(), $headers);
    }

    /**
     * Merge API Body.
     */
    final protected function mergeApiBody(array $body = []): array
    {
        return array_merge($this->getApiBody(), $body);
    }

    /**
     * Get API Endpoint.
     *
     * @param  array<int, string>|string  $path
     */
    protected function getApiEndpoint(array|string $path = []): Contracts\Endpoint
    {
        return new Endpoint($this->client->getApiEndpoint() ?? '', $path);
    }
}
