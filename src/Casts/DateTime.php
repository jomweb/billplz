<?php

namespace Billplz\Casts;

use DateTimeInterface;
use Laravie\Codex\Filter\Cast;

class DateTime extends Cast
{
    /**
     * Is value a valid object.
     */
    protected function isValid(mixed $value): bool
    {
        return $value instanceof DateTimeInterface;
    }

    /**
     * Cast value from object.
     *
     * @param  DateTimeInterface  $value
     */
    protected function fromCast(mixed $value): string
    {
        return $value instanceof DateTimeInterface ? $value->format('Y-m-d') : '';
    }

    /**
     * Cast value to object.
     */
    protected function toCast(mixed $value): DateTimeInterface
    {
        return new \DateTime($value);
    }
}
