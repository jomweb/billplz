<?php

namespace Billplz;

use GuzzleHttp\Psr7\Uri;

abstract class Request
{
    use WithSanitizer;

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
     * @param \Billplz\Sanitizer|null  $sanitizer
     */
    public function __construct(Client $client, Sanitizer $sanitizer = null)
    {
        $this->client = $client;

        $this->setSanitizer($sanitizer);
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

        if ($this->hasSanitizer()) {
            $body = $this->getSanitizer()->from($body);
        }

        return $this->client->send($method, $uri, $headers, $body)
                    ->setSanitizer($this->getSanitizer());
    }
}
