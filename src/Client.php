<?php

namespace Billplz;

use GuzzleHttp\Psr7\Uri;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Client\Common\HttpMethodsClient as HttpClient;

class Client
{
    /**
     * Http Client instance.
     *
     * @var \Http\Client\Common\HttpMethodsClient
     */
    protected $http;

    /**
     * Billplz API Key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Billplz API endpoint.
     *
     * @var string
     */
    protected $endpoint = 'https://www.billplz.com/api/v3';

    /**
     * Construct a new Billplz Client.
     *
     * @param \Http\Client\Common\HttpMethodsClient  $http
     * @param string  $apiKey
     */
    public function __construct(HttpClient $http, $apiKey)
    {
        $this->http   = $http;
        $this->apiKey = $apiKey;
    }

    /**
     * Make a client.
     *
     * @param string  $apiKey
     *
     * @return $this
     */
    public static function make($apiKey)
    {
        $client = new HttpClient(
            HttpClientDiscovery::find(),
            MessageFactoryDiscovery::find()
        );

        return new static($client, $apiKey);
    }

    /**
     * Use sandbox environment.
     *
     * @return $this
     */
    public function useSandbox()
    {
        $this->endpoint = 'https://billplz-staging.herokuapp.com/api/v3';

        return $this;
    }

    /**
     * Send the HTTP request.
     *
     * @param  string  $method
     * @param  \Psr\Http\Message\UriInterface|string  $url
     * @param  array  $headers
     * @param  array  $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send($method, $url, $headers = [], $data = [])
    {
        $uri = (new Uri($this->endpoint.'/'.$url))
                    ->withUserInfo($this->apiKey);

        $headers = $this->prepareRequestHeaders($headers);
        $body    = $this->prepareRequestBody($data, $headers);

        return $this->http->send($method, $uri, $headers, $body);
    }

    /**
     * Prepare request body.
     *
     * @param  mixed  $body
     * @param  array  $headers
     *
     * @return string
     */
    protected function prepareRequestBody($body = [], array $headers = [])
    {
        if ($headers['Content-Type'] == 'application/json') {
            return json_encode($body);
        }

        return http_build_query($data, null, '&');
    }

    /**
     * Prepare request headers.
     *
     * @param  array  $headers
     *
     * @return array
     */
    protected function prepareRequestHeaders(array $headers = [])
    {
        return $headers;
    }
}
