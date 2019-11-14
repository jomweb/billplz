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
     * Verify signature.
     */
    final public function verify(array $data, string $hash): bool
    {
        $keys = [];

        foreach ($this->attributes as $attribute) {
            \array_push($keys, $attribute.($data[$attribute] ?? ''));
        }

        $compared = \hash_hmac('sha256', \implode('|', $keys), $this->key);

        return \hash_equals($compared, $hash);
    }
}
