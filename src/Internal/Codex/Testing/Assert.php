<?php

namespace Laravie\Codex\Testing;

use ArrayAccess;
use PHPUnit\Framework\Assert as PHPUnit;

/**
 * @internal this class is not meant to be used or overwritten outside the framework itself
 */
abstract class Assert extends PHPUnit
{
    /**
     * Asserts that an array has a specified subset.
     */
    public static function assertArraySubset(
        mixed $subset,
        mixed $array,
        bool $checkForIdentity = false,
        string $msg = ''
    ): void {
        $subset = self::assertArrayLike($subset);
        $array = self::assertArrayLike($array);

        $constraint = new ArraySubset($subset, $checkForIdentity);

        PHPUnit::assertThat($array, $constraint, $msg);
    }

    /**
     * @return array<mixed, mixed>|ArrayAccess<mixed, mixed>
     */
    private static function assertArrayLike(mixed $value): array|ArrayAccess
    {
        if (! is_array($value) && ! $value instanceof ArrayAccess) {
            throw new \InvalidArgumentException('Argument array should be of type array or ArrayAccess.');
        }

        return $value;
    }
}
