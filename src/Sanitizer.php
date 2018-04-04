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
            'total' => new $money(),
            'amount' => new $money(),
            'due_at' => new $datetime(),
            'paid_amount' => new $money(),
            'paid_at' => new $datetime(),
            'split_payment' => [
                'fixed_cut' => new $money(),
            ],
        ];
    }
}
