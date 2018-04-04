<?php

namespace Billplz;

use Laravie\Codex\Sanitizer as BaseSanitizer;

class Sanitizer extends BaseSanitizer
{
    /**
     * Construct a new sanitizer.
     *
     * @param  array  $casters
     */
    public function __construct(array $casters = [])
    {
        $money = $casters['money'] ?? Casts\Ringgit::class;
        $datetime = $casters['datetime'] ?? Casts\DateTime::class;

        $this->casts = [
            'total' => $money,
            'amount' => $money,
            'due_at' => $datetime,
            'paid_amount' => $money,
            'paid_at' => $datetime,
            'split_payment' => [
                'fixed_cut' => $money,
            ],
        ];
    }
}
