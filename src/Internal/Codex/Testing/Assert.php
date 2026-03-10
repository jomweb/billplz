<?php

namespace Laravie\Codex\Testing;

use ArrayAccess;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Runner\Version;

if (class_exists(Version::class) && version_compare(Version::series(), '8.0', '>=')) {
    /**
     * @internal this class is not meant to be used or overwritten outside the framework itself
     */
    abstract class Assert extends PHPUnit
    {
        /**
         * Asserts that an array has a specified subset.
         *
         * @param  \ArrayAccess|array  $subset
         * @param  \ArrayAccess|array  $array
         */
        public static function assertArraySubset($subset, $array, bool $checkForIdentity = false, string $msg = ''): void
        {
            if (! (\is_array($subset) || $subset instanceof ArrayAccess)) {
                throw new \InvalidArgumentException('Argument #1 must be of type array or ArrayAccess');
            }

            if (! (\is_array($array) || $array instanceof ArrayAccess)) {
                throw new \InvalidArgumentException('Argument #2 must be of type array or ArrayAccess');
            }

            $constraint = new ArraySubset($subset, $checkForIdentity);

            PHPUnit::assertThat($array, $constraint, $msg);
        }
    }
} else {
    /**
     * @internal this class is not meant to be used or overwritten outside the framework itself
     */
    abstract class Assert extends PHPUnit
    {
        /**
         * Asserts that an array has a specified subset.
         *
         * @param  \ArrayAccess|array  $subset
         * @param  \ArrayAccess|array  $array
         */
        public static function assertArraySubset($subset, $array, bool $checkForIdentity = false, string $msg = ''): void
        {
            PHPUnit::assertArraySubset($subset, $array, $checkForIdentity, $msg);
        }
    }
}
