<?php

namespace Billplz;

use Laravie\Codex\Endpoint;
use Psr\Http\Message\UriInterface;
use Laravie\Codex\Request as BaseRequest;
use Laravie\Codex\Contracts\Endpoint as EndpointContract;
use Laravie\Codex\Contracts\Sanitizer as SanitizerContract;

abstract class Request extends BaseRequest
{
    /**
     * Get URI Endpoint.
     *
     * @param  array|string  $path
     *
     * @return \Laravie\Codex\Contracts\Endpoint
     */
    protected function getApiEndpoint($path = []): EndpointContract
    {
        if (is_array($path)) {
            array_unshift($path, $this->getVersion());
        } else {
            $path = [$this->getVersion(), $path];
        }

        return parent::getApiEndpoint($path);
    }

    /**
     * Resolve URI.
     *
     * @param  \Laravie\Codex\Endpoint  $endpoint
     *
     * @return \Psr\Http\Message\UriInterface
     */
    protected function resolveUri(Endpoint $endpoint): UriInterface
    {
        return parent::resolveUri($endpoint)
                    ->withUserInfo($this->client->getApiKey());
    }

    /**
     * Resolve the sanitizer class.
     *
     * @return \Billplz\Sanitizer
     */
    protected function sanitizeWith(): SanitizerContract
    {
        return new Sanitizer();
    }

    /**
     * Parse callback URL from request.
     *
     * @param  array  $body
     * @param  array|string  $url
     *
     * @return array
     */
    protected function parseRedirectAndCallbackUrlFromRequest(array $body, $url): array
    {
        if (is_string($url)) {
            $body['callback_url'] = $url;
        } elseif (is_array($url)) {
            $body['callback_url'] = $url['callback_url'] ?? null;
            $body['redirect_url'] = $url['redirect_url'] ?? null;
        }

        return $body;
    }
}
