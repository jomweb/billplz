<?php

namespace Billplz\Casts;

abstract class Cast
{
    /**
     * Cast value from object.
     *
     * @param  object  $value
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function from($value)
    {
        if (! $this->isValid($value)) {
            return $value;
        }

        return $this->fromCast($value);
    }

    /**
     * Cast value to object.
     *
     * @param  mixed  $value
     *
     * @return object|null
     */
    public function to($value)
    {
        if (is_null($value)) {
            return ;
        }

        return $this->toCast($value);
    }

    /**
     * Is value a valid object.
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    abstract protected function isValid($value);

    /**
     * Cast value from object.
     *
     * @param  object  $value
     *
     * @return mixed
     */
    abstract protected function fromCast($value);

    /**
     * Cast value to object.
     *
     * @param  mixed  $value
     *
     * @return object
     */
    abstract protected function toCast($value);
}
