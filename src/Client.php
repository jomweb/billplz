<?php

namespace Billplz;

use InvalidArgumentException;
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
    protected $endpoint = 'https://www.billplz.com/api';

    /**
     * Default version.
     *
     * @var string
     */
    protected $defaultVersion = 'v3';

    /**
     * List of supported API versions.
     *
     * @var array
     */
    protected $supportedVersions = [
        'v2' => 'Two',
        'v3' => 'Three',
    ];

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
        return $this->useCustomApiEndpoint('https://billplz-staging.herokuapp.com/api');
    }

    /**
     * Use custom endpoint.
     *
     * @param  string  $endpoint
     *
     * @return $this
     */
    public function useCustomApiEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Use different API version.
     *
     * @param  string  $version
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function useVersion($version)
    {
        if (! array_key_exists($version, $this->supportedVersions)) {
            throw new InvalidArgumentException("API version {$version} is not supported");
        }

        $this->defaultVersion = $version;

        return $this;
    }

    /**
     * Get API endpoint URL.
     *
     * @return string
     */
    public function getApiEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Get API Key.
     *
     * @return string|null
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get collection resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function collection($version = null)
    {
        return $this->getVersionedResource('Collection', $version);
    }

    /**
     * Get bill resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function bill($version = null)
    {
        return $this->getVersionedResource('Bill', $version);
    }

    /**
     * Send the HTTP request.
     *
     * @param  string  $method
     * @param  \Psr\Http\Message\UriInterface|string  $uri
     * @param  array  $headers
     * @param  array  $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send($method, $uri, $headers = [], $data = [])
    {
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
        if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') {
            return json_encode($body);
        }

        return http_build_query($body, null, '&');
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

    /**
     * Get versioned resource (service).
     *
     * @param  string  $service
     * @param  string|null  $version
     *
     * @throws \InvalidArgumentException
     *
     * @return object
     */
    protected function getVersionedResource($service, $version = null)
    {
        if (is_null($version) || ! array_key_exists($version, $this->supportedVersions)) {
            $version = $this->defaultVersion;
        }

        $class = sprintf('%s\%s\%s', __NAMESPACE__, $this->supportedVersions[$version], $service);

        return new $class($this);
    }
}
