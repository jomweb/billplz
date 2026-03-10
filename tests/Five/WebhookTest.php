<?php

use Laravie\Codex\Contracts\Response;

beforeEach(function (): void {
    $this->proxyApiVersion = 'v4';
});

it('resolves the correct version', function (): void {
    $payment = $this->makeClient()->uses('Webhook', 'v5');

    expect($payment)->toBeInstanceOf('Billplz\Four\Webhook');
    expect($payment->getVersion())->toBe($this->proxyApiVersion ?? $this->apiVersion);
});

it('can get webhook rank', function (): void {
    $expected = '{"rank":1.2}';

    $faker = $this->expectRequest('GET', 'webhook_rank')
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->uses('Webhook')->rank();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});
