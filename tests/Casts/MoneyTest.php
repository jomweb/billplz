<?php

use Billplz\Casts\Money;
use Money\Money as MoneyValue;

it('casts money object to amount string', function (): void {
    $cast = new Money;
    $money = MoneyValue::MYR('123');

    expect($cast->from($money))->toBe('123');
});

it('casts numeric value to money object', function (): void {
    $cast = new Money;

    expect($cast->to(123))->toBeInstanceOf(MoneyValue::class);
    expect($cast->to('456')->getAmount())->toBe('456');
});

it('casts non-numeric value to zero money', function (): void {
    $cast = new Money;

    expect($cast->to('not-number')->getAmount())->toBe('0');
});
