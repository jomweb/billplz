<?php

namespace Billplz;

use GuzzleHttp\Psr7\Uri;

abstract class Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version;

    /**
     * The Billplz client.
     *
     * @var \Billplz\Client
     */
    protected $client;

    /**
     * Get API endpoint.
     *
     * @param  string  $name
     *
     * @return array
     */
    protected function endpoint($name, array $headers = [])
    {
        $client = $this->client;

        $uri = (new Uri(sprintf('%s/%s/%s', $client->getApiEndpoint(), $this->version, $name)))
                    ->withUserInfo($client->getApiKey());

        return [$uri, $headers];
    }
}
