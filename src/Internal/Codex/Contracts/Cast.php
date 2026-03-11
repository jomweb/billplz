<?php

namespace Laravie\Codex\Contracts;

interface Cast
{
    /**
     * Cast value from object.
     *
     * @param  object  $value
     */
    public function from(mixed $value): mixed;

    /**
     * Cast value to object.
     */
    public function to(mixed $value): ?object;
}
