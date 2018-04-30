<?php

namespace Billplz\Casts;

use Laravie\Codex\Cast;

class Ringgit extends Cast
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
        return $value instanceof \Money\Money || $value instanceof \Duit\MYR;
    }

    /**
     * Cast value from object.
     *
     * @param  \Money\Money|\Duit\MYR  $value
     *
     * @return string
     */
    protected function fromCast($value): string
    {
        return $value->getAmount();
    }

    /**
     * Cast value to object.
     *
     * @param  mixed  $value
     *
     * @return \Duit\MYR
     */
    protected function toCast($value): \Duit\MYR
    {
        return \Duit\MYR::given($value);
    }
}
