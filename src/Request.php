<?php

namespace Billplz;

use Laravie\Codex\Contracts\Endpoint;
use Laravie\Codex\Request as BaseRequest;

abstract class Request extends BaseRequest
{
    /**
     * Get URI Endpoint.
     *
     * @param  array|string  $path
     *
     * @return \Laravie\Codex\Contracts\Endpoint
     */
    protected function getApiEndpoint($path = []): Endpoint
    {
        $path = [$this->getVersion(), $path];

        return parent::getApiEndpoint($path)
                    ->withUserInfo($this->client->getApiKey());
    }

    /**
     * Resolve the sanitizer class.
     *
     * @return \Billplz\Sanitizer
     */
    protected function sanitizeWith(): Sanitizer
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
    final protected function parseRedirectAndCallbackUrlFromRequest(array $body, $url): array
    {
        if (is_string($url)) {
            $body['callback_url'] = $url;
        } elseif (is_array($url)) {
            $body['callback_url'] = $url['callback_url'] ?? null;
            $body['redirect_url'] = $url['redirect_url'] ?? null;
        }

        return $body;
    }

    /**
     * Proxy route response via version.
     *
     * @param  string   $version
     * @param  callable $callback
     *
     * @return \Billplz\Response
     */
    final protected function proxyRequestUsingVersion(string $version, callable $callback): Response
    {
        $currentVersion = $this->version;

        try {
            $this->version = $version;

            return call_user_func($callback);
        } finally {
            $this->version = $currentVersion;
        }
    }
}
