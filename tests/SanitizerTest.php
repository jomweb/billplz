<?php

use Billplz\Sanitizer;

it('has proper signature', function (): void {
    expect(new Sanitizer())->toBeInstanceOf('Laravie\Codex\Filter\Sanitizer');
});
