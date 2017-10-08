<?php

namespace Billplz\Casts;

use Laravie\Codex\Cast;

class Money extends Cast
{
    /**
     * Is value a valid object.
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    protected function isValid($value)
    {
        return $value instanceof \Money\Money || $value instanceof \Duit\MYR;
    }

    /**
     * Cast value from object.
     *
     * @param  \Money\Money  $value
     *
     * @return int
     */
    public function fromCast($value)
    {
        return $value->getAmount();
    }

    /**
     * Cast value to object.
     *
     * @param  mixed  $value
     *
     * @return \Money\Money
     */
    protected function toCast($value)
    {
        if (class_exists(\Duit\MYR::class, false)) {
            return \Duit\MYR::given($value);
        }

        return \Money\Money::MYR($value);
    }
}
