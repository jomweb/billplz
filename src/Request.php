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
        $domain = $this->client->getApiEndpoint();
        $uri = new Uri(sprintf('%s/%s/%s', $domain, $this->getVersion(), $endpoint));

        return $uri->withUserInfo($this->client->getApiKey());
    }
}
