<?php

namespace Billplz;

use Laravie\Codex\Discovery;
use Laravie\Codex\Client as BaseClient;
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
        'v3' => 'Three',
        'v4' => 'Four',
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
        return new static(Discovery::client(), $apiKey);
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
     * Get transaction resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function transaction($version = null)
    {
        return $this->resource('Bill.Transaction', $version);
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
