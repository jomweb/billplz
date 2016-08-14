<?php

namespace Billplz;

use GuzzleHttp\Psr7\Uri;
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
        $this->http = $http;
        $this->apiKey = $apiKey;
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

        return $this->http->send($method, $uri, $headers, http_build_query($data, null, '&'));
    }
}
