<?php

namespace Billplz;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
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
    protected $apiEndpoint = 'https://www.billplz.com/api';

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
        $this->http = $http;
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
     * @param  string  $apiEndpoint
     *
     * @return $this
     */
    public function useCustomApiEndpoint($apiEndpoint)
    {
        $this->apiEndpoint = $apiEndpoint;

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
        return $this->apiEndpoint;
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
        return $this->resource('Collection', $version);
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
        return $this->resource('Bill', $version);
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
    public function resource($service, $version = null)
    {
        if (is_null($version) || ! array_key_exists($version, $this->supportedVersions)) {
            $version = $this->defaultVersion;
        }

        $name = str_replace('.', '\\', $service);
        $class = sprintf('%s\%s\%s', __NAMESPACE__, $this->supportedVersions[$version], $name);

        if (! class_exists($class)) {
            throw new InvalidArgumentException("Resource [{$service}] for version [{$version}] is not available");
        }

        return new $class($this);
    }

    /**
     * Send the HTTP request.
     *
     * @param  string  $method
     * @param  \Psr\Http\Message\UriInterface|string  $uri
     * @param  array  $headers
     * @param  \Psr\Http\Message\StreamInterface|array|null  $body
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send($method, $uri, array $headers = [], $body = [])
    {
        $headers = $this->prepareRequestHeaders($headers);
        list($headers, $body) = $this->prepareRequestPayloads($headers, $body);

        return $this->http->send($method, $uri, $headers, $body);
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
     * Prepare request payloads.
     *
     * @param  array  $headers
     * @param  mixed  $body
     *
     * @return array
     */
    protected function prepareRequestPayloads(array $headers = [], $body = [])
    {
        if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') {
            $body = json_encode($body);
        } elseif (! $body instanceof StreamInterface) {
            $body = http_build_query($body, null, '&');
        }

        return [$headers, $body];
    }
}
