<?php

namespace Billplz;

use GuzzleHttp\Psr7\Uri;
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
        $to = sprintf('%s/%s/%s', $this->client->getApiEndpoint(), $this->getVersion(), $endpoint);

        return (new Uri($to))->withUserInfo($this->client->getApiKey());
    }
}
