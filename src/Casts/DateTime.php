<?php

namespace Billplz\Casts;

use DateTimeInterface;
use Laravie\Codex\Filter\Cast;

class DateTime extends Cast
{
    /**
     * Is value a valid object.
     *
     * @param  mixed  $value
     */
    protected function isValid($value): bool
    {
        return $value instanceof \DateTimeInterface;
    }

    /**
     * Cast value from object.
     *
     * @param  \DateTimeInterface  $value
     */
    protected function fromCast($value): string
    {
        return $value->format('Y-m-d');
    }

    /**
     * Cast value to object.
     *
     * @param  mixed  $value
     */
    protected function toCast($value): DateTimeInterface
    {
        return new \DateTime($value);
    }
}
