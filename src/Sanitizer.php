<?php

namespace Billplz;

class Sanitizer extends \Laravie\Codex\Filter\Sanitizer
{
    /**
     * Construct a new sanitizer.
     *
     * @param  array<string, class-string<\Laravie\Codex\Contracts\Cast>>  $casters
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
