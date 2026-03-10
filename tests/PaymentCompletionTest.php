<?php

use Billplz\PaymentCompletion;

it('can generate without redirect url', function (): void {
    $payment = new PaymentCompletion('http://example.com/webhook/');

    expect($payment->webhookUrl())->toBe('http://example.com/webhook/');
    expect($payment->redirectUrl())->toBeNull();
    expect($payment->toArray())->toBe([
        'callback_url' => 'http://example.com/webhook/',
        'redirect_url' => null,
    ]);
});

it('can generate with redirect url', function (): void {
    $payment = new PaymentCompletion('http://example.com/webhook/', 'http://example.com/redirect/');

    expect($payment->webhookUrl())->toBe('http://example.com/webhook/');
    expect($payment->redirectUrl())->toBe('http://example.com/redirect/');
    expect($payment->toArray())->toBe([
        'callback_url' => 'http://example.com/webhook/',
        'redirect_url' => 'http://example.com/redirect/',
    ]);
});
