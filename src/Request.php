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
        list($uri, $headers) = $this->endpoint($path, $headers);

        return $this->client->send($method, $uri, $headers, $body);
    }

    /**
     * Resolve API Endpoint URI and headers.
     *
     * @param  string  $path
     * @param  array  $headers
     *
     * @return array
     *
     * @deprecated v0.4.2 To be removed in v0.5.0
     */
    protected function endpoint($path, array $headers = [])
    {
        $domain = $this->client->getApiEndpoint();

        $uri = (new Uri(sprintf('%s/%s/%s', $domain, $this->version, $path)))
                    ->withUserInfo($this->client->getApiKey());

        return [$uri, $headers];
    }
}
