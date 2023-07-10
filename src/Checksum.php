<?php

namespace Billplz;

class Checksum
{
    //V5 API introduces new security measures.
    public static function create(string $key, array $attributes): string
    {
        return hash_hmac('sha512', implode('', $attributes), (string) $key);
    }
}
