<?php

use Billplz\Casts\DateTime;

it('can cast datetime to string', function (): void {
    $cast = new DateTime();

    expect($cast->from(new \DateTime('2018-01-01 11:00:01')))->toBe('2018-01-01');
});

it('wouldnt cast datetime if not validated', function (): void {
    $cast = new DateTime();

    expect($cast->from('foo'))->toBe('foo');
});

it('can cast string to datetime', function (): void {
    $cast = new DateTime();

    expect($cast->to('2018-01-01'))->toBeInstanceOf('DateTimeInterface');
});

it('can cast none string to datetime', function (): void {
    $cast = new DateTime();

    expect($cast->to(null))->toBeNull();
});
