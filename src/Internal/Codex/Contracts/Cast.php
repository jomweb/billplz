<?php

namespace Laravie\Codex\Contracts;

interface Cast
{
    /**
     * Cast value from object.
     *
     * @param  object  $value
     * @return mixed
     */
    public function from($value);

    /**
     * Cast value to object.
     *
     * @param  mixed  $value
     * @return object|null
     */
    public function to($value);
}
