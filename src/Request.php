<?php

namespace Billplz;

use Laravie\Codex\Request as BaseRequest;

abstract class Request extends BaseRequest
{
    /**
     * Get URI Endpoint.
     *
     * @param  string  $endpoint
     *
     * @return \GuzzleHttp\Psr7\Uri
     */
    protected function getUriEndpoint($endpoint)
    {
        return parent::getUriEndpoint($endpoint)
                    ->withUserInfo($this->client->getApiKey());
    }
}
