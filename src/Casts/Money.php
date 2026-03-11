<?php

namespace Billplz\Casts;

use Laravie\Codex\Filter\Cast;
use Money\Money as MoneyValue;

class Money extends Cast
{
    /**
     * Is value a valid object.
     */
    protected function isValid(mixed $value): bool
    {
        return $value instanceof MoneyValue;
    }

    /**
     * Cast value from object.
     *
     * @param  MoneyValue  $value
     */
    protected function fromCast(mixed $value): string
    {
        return $value instanceof MoneyValue ? $value->getAmount() : '0';
    }

    /**
     * Cast value to object.
     */
    protected function toCast(mixed $value): object
    {
        if (\is_int($value) || \is_float($value) || \is_numeric($value)) {
            return MoneyValue::MYR((string) $value);
        }

        return MoneyValue::MYR('0');
    }
}
