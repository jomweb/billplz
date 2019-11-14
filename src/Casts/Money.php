<?php

namespace Billplz\Casts;

use Laravie\Codex\Filter\Cast;

class Money extends Cast
{
    /**
     * Is value a valid object.
     *
     * @param  mixed  $value
     */
    protected function isValid($value): bool
    {
        return $value instanceof \Money\Money || $value instanceof \Duit\MYR;
    }

    /**
     * Cast value from object.
     *
     * @param  \Money\Money|\Duit\MYR  $value
     */
    protected function fromCast($value): string
    {
        return $value->getAmount();
    }

    /**
     * Cast value to object.
     *
     * @param  mixed  $value
     */
    protected function toCast($value): \Money\Money
    {
        return \Money\Money::MYR($value);
    }
}
