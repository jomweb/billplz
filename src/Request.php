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
     * Construct a new Collection.
     *
     * @param \Billplz\Client  $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send API request.
     *
     * @param  string  $method
     * @param  string  $path
     * @param  array  $headers
     * @param  array  $body
     *
     * @return \Billplz\Reponse
     */
    protected function send($method, $path, array $headers = [], array $body = [])
    {
        $domain = $this->client->getApiEndpoint();

        $uri = (new Uri(sprintf('%s/%s/%s', $domain, $this->version, $path)))
                    ->withUserInfo($this->client->getApiKey());

        return $this->client->send($method, $uri, $headers, $body);
    }
}
