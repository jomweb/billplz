<?php

namespace Billplz\Casts;

class DateTime extends Cast
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
        return $value instanceof \DateTimeInterface;
    }

    /**
     * Cast value from object.
     *
     * @param  \DateTimeInterface  $value
     *
     * @return string
     */
    public function fromCast($value)
    {
        return $value->format('Y-m-d');
    }

    /**
     * Cast value to object.
     *
     * @param  mixed  $value
     *
     * @return \DateTime
     */
    protected function toCast($value)
    {
        return \DateTime::createFromFormat('Y-m-d', $value);
    }
}
