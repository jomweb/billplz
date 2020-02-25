<?php

namespace Billplz;

class Signature
{
    /**
     * Redirect parameters constants.
     */
    public const REDIRECT_PARAMETERS = [
        'billplzid', 'billplzpaid_at', 'billplzpaid',
    ];

    /**
     * Webhook parameters constants.
     */
    public const WEBHOOK_PARAMETERS = [
        'amount', 'collection_id', 'due_at', 'email', 'id', 'mobile', 'name',
        'paid_amount', 'paid_at', 'paid', 'state', 'url',
    ];

    /**
     * Signature key.
     *
     * @var string
     */
    protected $key;

    /**
     * List of attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Construct a new signature verification.
     */
    public function __construct(string $key, array $attributes)
    {
        $this->key = $key;
        $this->attributes = $attributes;
    }

    /**
     * Create signature.
     */
    final public function create(array $data): string
    {
        $keys = [];

        foreach ($this->attributes as $attribute) {
            \array_push($keys, $attribute.($data[$attribute] ?? ''));
        }

        return \hash_hmac('sha256', \implode('|', $keys), $this->key);
    }

    /**
     * Verify signature.
     */
    final public function verify(array $data, string $hash): bool
    {
        return \hash_equals($this->create($data), $hash);
    }
}
