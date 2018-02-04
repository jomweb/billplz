<?php

namespace Billplz;

use Laravie\Codex\Sanitizer as BaseSanitizer;

class Sanitizer extends BaseSanitizer
{
    /**
     * Construct a new sanitizer.
     *
     * @param  array  $casts
     */
    public function __construct(array $casts = [])
    {
        $this->casts = array_merge($casts, [
            'total' => new Casts\Money(),
            'amount' => new Casts\Money(),
            'due_at' => new Casts\DateTime(),
            'paid_amount' => new Casts\Money(),
            'paid_at' => new Casts\DateTime(),
            'split_payment' => [
                'fixed_cut' => new Casts\Money(),
            ],
        ]);
    }
}
