<?php

namespace Billplz;

class Signature
{
    /**
     * Redirect parameters constants.
     */
    public const REDIRECT_PARAMETERS = [
        'billplzid', 'billplzpaid_at', 'billplzpaid', 'billplztransaction_id', 'billplztransaction_status',
    ];

    /**
     * Webhook parameters constants.
     */
    public const WEBHOOK_PARAMETERS = [
        'amount', 'collection_id', 'due_at', 'email', 'id', 'mobile', 'name',
        'paid_amount', 'paid_at', 'paid', 'state', 'transaction_id', 'transaction_status', 'url',
    ];

    /**
     * List of required parameters returned by webhook and redirect url.
     *
     * @var array<int, string>
     */
    protected array $requiredParameters = [
        'billplzid', 'billplzpaid_at', 'billplzpaid', 'amount', 'collection_id', 'due_at', 'email', 'id', 'mobile', 'name',
        'paid_amount', 'paid_at', 'paid', 'state', 'url',
    ];

    /**
     * Construct a new signature verification.
     */
    public function __construct(
        protected ?string $key,
        protected array $attributes
    ) {
        //
    }

    /**
     * Construct a new signature verification for webhook.
     *
     * @return static
     */
    public static function redirect(?string $key)
    {
        return new static($key, self::REDIRECT_PARAMETERS);
    }

    /**
     * Construct a new signature verification for webhook.
     *
     * @return static
     */
    public static function webhook(?string $key)
    {
        return new static($key, self::WEBHOOK_PARAMETERS);
    }

    /**
     * Signature has key.
     */
    public function hasKey(): bool
    {
        return ! \is_null($this->key);
    }

    /**
     * Create signature.
     */
    final public function create(array $data): string
    {
        $keys = [];

        foreach ($this->attributes as $attribute) {
            if (isset($data[$attribute]) || array_search($attribute, $this->requiredParameters)) {
                array_push($keys, $attribute.($data[$attribute] ?? ''));
            }
        }

        return hash_hmac('sha256', implode('|', $keys), (string) $this->key);
    }

    /**
     * Verify signature.
     */
    final public function verify(array $data, string $hash): bool
    {
        return hash_equals($this->create($data), $hash);
    }
}
