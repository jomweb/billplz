<?php

namespace Billplz;

use Laravie\Codex\Contracts\Cast;

class Sanitizer extends \Laravie\Codex\Filter\Sanitizer
{
    /**
     * Construct a new sanitizer.
     *
     * @param  array<string, class-string<Cast>>  $casters
     */
    public function __construct(array $casters = [])
    {
        $money = $casters['money'] ?? Casts\Money::class;
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
