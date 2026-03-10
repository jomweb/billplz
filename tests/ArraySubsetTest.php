<?php

use Laravie\Codex\Testing\ArraySubset;

it('describes the subset with the current phpunit exporter API', function (): void {
    $constraint = new ArraySubset(['foo' => 'bar']);

    expect($constraint->toString())->toContain("'foo' => 'bar'");
});
