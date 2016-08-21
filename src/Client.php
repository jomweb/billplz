<?php

namespace Billplz;

use Laravie\Codex\Response;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Http\Discovery\HttpClientDiscovery;
use Laravie\Codex\Client as BaseClient;
use Psr\Http\Message\ResponseInterface;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Client\Common\HttpMethodsClient as HttpClient;

class Client extends BaseClient
{
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
     * Default API version.
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
        return new static(static::makeHttpClient(), $apiKey);
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
     * Resolve the sanitizer class.
     *
     * @return \Billplz\Sanitizer
     */
    protected function sanitizeWith()
    {
        return new Sanitizer();
    }

    /**
     * Get resource default namespace.
     *
     * @return string
     */
    protected function getResourceNamespace()
    {
        return __NAMESPACE__;
    }
}
