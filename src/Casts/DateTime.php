<?php

namespace Billplz\Casts;

use Carbon\Carbon;
use DateTimeInterface;
use Laravie\Codex\Cast;

class DateTime extends Cast
{
    /**
     * Is value a valid object.
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    protected function isValid($value): bool
    {
        return $value instanceof \DateTimeInterface;
    }

    /**
     * Cast value from object.
     *
     * @param  \DateTimeInterface  $value
     *
     * @return string
     */
    protected function fromCast($value): string
    {
        return $value->format('Y-m-d');
    }

    /**
     * Cast value to object.
     *
     * @param  mixed  $value
     *
     * @return \DateTimeInterface
     */
    protected function toCast($value): DateTimeInterface
    {
        if (class_exists(Carbon::class, false)) {
            return Carbon::parse($value);
        }

        return new \DateTime($value);
    }
}
