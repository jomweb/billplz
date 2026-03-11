<?php

namespace Laravie\Codex\Filter;

use Laravie\Codex\Contracts\Cast as CastContract;

abstract class Cast implements CastContract
{
    /**
     * Cast value from object.
     *
     * @param  object  $value
     */
    public function from(mixed $value): mixed
    {
        return $this->isValid($value)
            ? $this->fromCast($value)
            : $value;
    }

    /**
     * Cast value to object.
     */
    public function to(mixed $value): ?object
    {
        return ! \is_null($value)
            ? $this->toCast($value)
            : null;
    }

    /**
     * Is value a valid object.
     */
    abstract protected function isValid(mixed $value): bool;

    /**
     * Cast value from object.
     *
     * @param  object  $value
     */
    abstract protected function fromCast(mixed $value): mixed;

    /**
     * Cast value to object.
     */
    abstract protected function toCast(mixed $value): object;
}
