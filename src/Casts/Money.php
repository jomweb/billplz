<?php

namespace Billplz\Casts;

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
        return $value instanceof \Money\Money;
    }

    /**
     * Cast value from object.
     *
     * @param  object  $value
     *
     * @return mixed
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
     * @return object
     */
    protected function toCast($value)
    {
        return \Money\Money::MYR($value);
    }
}
