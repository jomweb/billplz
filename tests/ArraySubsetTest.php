<?php

use Laravie\Codex\Testing\ArraySubset;

it('describes the subset with the current phpunit exporter API', function (): void {
    $constraint = new ArraySubset(['foo' => 'bar']);

    expect($constraint->toString())->toContain("'foo' => 'bar'");
});

it('casts scalar haystack values before array subset evaluation', function (): void {
    $constraint = new ArraySubset(['0' => 'f']);

    expect($constraint->evaluate('bar', '', true))->toBeFalse();
});
