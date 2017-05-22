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
        return parent::getApiEndpoint([$this->getVersion(), $path]);
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
}
