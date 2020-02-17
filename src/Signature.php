<?php

namespace Billplz;

class Signature
{
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
