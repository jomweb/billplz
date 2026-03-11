<?php

namespace Laravie\Codex\Testing;

use ArrayAccess;
use ArrayObject;
use PHPUnit\Framework\Constraint\Constraint;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter;

/**
 * @internal this class is not meant to be used or overwritten outside the framework itself
 */
final class ArraySubset extends Constraint
{
    /**
     * @var iterable<mixed, mixed>|ArrayAccess<mixed, mixed>
     */
    private iterable|ArrayAccess $subset;

    private bool $strict;

    /**
     * Create a new array subset constraint instance.
     *
     * @param  iterable<mixed, mixed>|ArrayAccess<mixed, mixed>  $subset
     */
    public function __construct(iterable|ArrayAccess $subset, bool $strict = false)
    {
        $this->strict = $strict;
        $this->subset = $subset;
    }

    /**
     * Evaluates the constraint for parameter $other.
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     */
    public function evaluate(mixed $other, string $description = '', bool $returnResult = false): ?bool
    {
        // type cast $other & $this->subset as an array to allow
        // support in standard array functions.
        $other = $this->toArray($other);
        $this->subset = $this->toArray($this->subset);

        $patched = array_replace_recursive($other, $this->subset);

        if ($this->strict) {
            $result = $other === $patched;
        } else {
            $result = $other == $patched;
        }

        if ($returnResult) {
            return $result;
        }

        if (! $result) {
            $f = new ComparisonFailure(
                $patched,
                $other,
                var_export($patched, true),
                var_export($other, true)
            );

            $this->fail($other, $description, $f);
        }

        return null;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return 'has the subset '.(new Exporter)->export($this->subset);
    }

    /**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     */
    protected function failureDescription(mixed $other): string
    {
        return 'an array '.$this->toString();
    }

    /**
     * @return array<mixed, mixed>
     */
    private function toArray(mixed $other): array
    {
        if (\is_array($other)) {
            return $other;
        }

        if ($other instanceof ArrayObject) {
            return $other->getArrayCopy();
        }

        if (! ($other instanceof \Traversable)) {
            return (array) $other;
        }

        return iterator_to_array($other);
    }
}
