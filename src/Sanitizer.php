<?php

namespace Billplz;

class Sanitizer extends \Laravie\Codex\Filter\Sanitizer
{
    /**
     * Construct a new sanitizer.
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
