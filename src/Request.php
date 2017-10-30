<?php

namespace Billplz;

use GuzzleHttp\Psr7\Uri;
use Laravie\Codex\Endpoint;
use Laravie\Codex\Request as BaseRequest;

abstract class Request extends BaseRequest
{
    /**
     * Get URI Endpoint.
     *
     * @param  array|string  $path
     *
     * @return \Laravie\Codex\Endpoint
     */
    protected function getApiEndpoint($path = [])
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
     * @return \GuzzleHttp\Psr7\Uri
     */
    protected function resolveUri(Endpoint $endpoint)
    {
        return parent::resolveUri($endpoint)
                    ->withUserInfo($this->client->getApiKey());
    }

    /**
     * Resolve the sanitizer class.
     *
     * @return \Billplz\Sanitizer
     */
    protected function sanitizeWith()
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
    protected function parseRedirectAndCallbackUrlFromRequest(array $body, $url)
    {
        if (is_string($url)) {
            $body['callback_url'] = $url;
        } elseif (is_array($url)) {
            $body['callback_url'] = isset($url['callback_url']) ? $url['callback_url'] : null;
            $body['redirect_url'] = isset($url['redirect_url']) ? $url['redirect_url'] : null;
        }

        return $body;
    }
}
