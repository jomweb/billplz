<?php

namespace Billplz;

use Http\Client\Common\HttpMethodsClient as HttpClient;
use Laravie\Codex\Discovery;

class Client extends \Laravie\Codex\Client
{
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
    protected $defaultVersion = 'v4';

    /**
     * List of supported API versions.
     *
     * @var array<string, string>
     */
    protected $supportedVersions = [
        'v3' => 'Three',
        'v4' => 'Four',
        'v5' => 'Five',
    ];

    /**
     * Construct a new Billplz Client.
     */
    public function __construct(
        HttpClient $http,
        protected string $apiKey,
        protected ?string $signatureKey = null
    ) {
        $this->http = $http;
    }

    /**
     * Make a client.
     *
     * @return static
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
        return $this->useCustomApiEndpoint('https://www.billplz-sandbox.com/api');
    }

    /**
     * Get API Key.
     */
    final public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Set API Key.
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
     */
    final public function getSignatureKey(): ?string
    {
        return $this->signatureKey;
    }

    /**
     * Set X-Signature Key.
     *
     * @return $this
     */
    final public function setSignatureKey(?string $signatureKey = null): self
    {
        $this->signatureKey = $signatureKey;

        return $this;
    }

    /**
     * Get Collection resource.
     *
     * @return \Billplz\Contracts\Collection
     */
    final public function collection(?string $version = null): Contracts\Collection
    {
        return $this->uses('Collection', $version);
    }

    /**
     * Get Open Collection resource.
     *
     * @return \Billplz\Contracts\OpenCollection
     */
    final public function openCollection(?string $version = null): Contracts\OpenCollection
    {
        return $this->uses('OpenCollection', $version);
    }

    /**
     * Get bill resource.
     *
     * @return \Billplz\Contracts\Bill
     */
    final public function bill(?string $version = null): Contracts\Bill
    {
        return $this->uses('Bill', $version);
    }

    /**
     * Get transaction resource.
     *
     * @return \Billplz\Contracts\Bill\Transaction
     */
    final public function transaction(?string $version = null): Contracts\Bill\Transaction
    {
        return $this->uses('Bill.Transaction', $version);
    }

    /**
     * Get payout instruction collection resource.
     *
     * @return \Billplz\Contracts\Collection\Payout
     */
    final public function payoutCollection(): Contracts\Collection\Payout
    {
        return $this->uses('Collection.Payout', 'v4');
    }

    /**
     * Get payout instruction resource.
     *
     * @return \Billplz\Contracts\Payout
     */
    final public function payout(): Contracts\Payout
    {
        return $this->uses('Payout', 'v4');
    }

    /**
     * Get payout instruction resource.
     *
     * @return \Billplz\Contracts\PaymentOrder
     */
    final public function paymentOrder(): Contracts\PaymentOrder
    {
        return $this->uses('PaymentOrder', 'v5');
    }

    /**
     * Get payout instruction collection resource.
     *
     * @return \Billplz\Contracts\PaymentOrderCollection
     */
    final public function paymentOrderCollection(): Contracts\PaymentOrderCollection
    {
        return $this->uses('PaymentOrderCollection', 'v5');
    }

    /**
     * Get bank resource.
     *
     * @return \Billplz\Contracts\BankAccount
     */
    final public function bank(?string $version = null): Contracts\BankAccount
    {
        return $this->uses('BankAccount', $version);
    }

    /**
     * Get card resource.
     *
     * @return \Billplz\Contracts\Card
     */
    final public function card(): Contracts\Card
    {
        return $this->uses('Card', 'v4');
    }

    /**
     * Get resource default namespace.
     */
    final protected function getResourceNamespace(): string
    {
        return __NAMESPACE__;
    }
}
