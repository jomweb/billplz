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
     * Billplz X-Signature Key.
     *
     * @var string|null
     */
    protected $signatureKey;

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
    ];

    /**
     * Construct a new Billplz Client.
     *
     * @param \Http\Client\Common\HttpMethodsClient  $http
     * @param string  $apiKey
     * @param string|null $signatureKey
     */
    public function __construct(HttpClient $http, $apiKey, $signatureKey)
    {
        $this->http = $http;

        $this->setApiKey($apiKey);
        $this->setSignatureKey($signatureKey);
    }

    /**
     * Make a client.
     *
     * @param string  $apiKey
     * @param string|null $signatureKey
     *
     * @return $this
     */
    public static function make($apiKey, $signatureKey = null)
    {
        return new static(Discovery::client(), $apiKey, $signatureKey);
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
     * Set API Key.
     *
     * @param  string  $apiKey
     *
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get X-Signature Key.
     *
     * @return string|null
     */
    public function getSignatureKey()
    {
        return $this->signatureKey;
    }

    /**
     * Set X-Signature Key.
     *
     * @param  string|null  $signatureKey
     *
     * @return $this
     */
    public function setSignatureKey($signatureKey = null)
    {
        $this->signatureKey = $signatureKey;

        return $this;
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
     * Get check resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function check($version = null)
    {
        return $this->resource('Check', $version);
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
     * Get bank resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function bank($version = null)
    {
        return $this->resource('Bank', $version);
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
