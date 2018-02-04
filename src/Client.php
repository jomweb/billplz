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
        'v4' => 'Four',
    ];

    /**
     * Construct a new Billplz Client.
     *
     * @param \Http\Client\Common\HttpMethodsClient  $http
     * @param string  $apiKey
     * @param string|null $signatureKey
     */
    public function __construct(HttpClient $http, string $apiKey, ?string $signatureKey = null)
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
    public static function make(string $apiKey, ?string $signatureKey = null)
    {
        return new static(Discovery::client(), $apiKey, $signatureKey);
    }

    /**
     * Use sandbox environment.
     *
     * @return $this
     */
    final public function useSandbox(): self
    {
        return $this->useCustomApiEndpoint('https://billplz-staging.herokuapp.com/api');
    }

    /**
     * Get API Key.
     *
     * @return string
     */
    final public function getApiKey(): string
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
    final public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get X-Signature Key.
     *
     * @return string|null
     */
    final public function getSignatureKey(): ?string
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
    final public function setSignatureKey(?string $signatureKey = null): self
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
    public function collection(?string $version = null)
    {
        return $this->uses('Collection', $version);
    }

    /**
     * Get bill resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function bill(?string $version = null)
    {
        return $this->uses('Bill', $version);
    }

    /**
     * Get check resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function check(?string $version = null)
    {
        return $this->uses('Check', $version);
    }

    /**
     * Get transaction resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function transaction(?string $version = null)
    {
        return $this->uses('Bill.Transaction', $version);
    }

    /**
     * Get mass payment instruction collection resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function massPaymentCollection(?string $version = null)
    {
        return $this->uses('Collection.MassPayment', $version);
    }

    /**
     * Get mass payment instruction resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function massPayment(?string $version = null)
    {
        return $this->uses('MassPayment', $version);
    }

    /**
     * Get bank resource.
     *
     * @param  string|null  $version
     *
     * @return object
     */
    public function bank(?string $version = null)
    {
        return $this->uses('Bank', $version);
    }

    /**
     * Get resource default namespace.
     *
     * @return string
     */
    final protected function getResourceNamespace(): string
    {
        return __NAMESPACE__;
    }
}
