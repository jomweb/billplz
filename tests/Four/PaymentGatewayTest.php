<?php

use Laravie\Codex\Contracts\Response;

beforeEach(function (): void {
    $this->apiVersion = 'v4';
});

it('resolves the correct version', function (): void {
    $payment = $this->makeClient()->uses('PaymentGateway', 'v4');

    expect($payment)->toBeInstanceOf('Billplz\Four\PaymentGateway');
    expect($payment->getVersion())->toBe('v4');
});

it('can get payment gateway index', function (): void {
    $expected = '{"payment_gateways":[{"code":"MBU0227","active":true,"category":"fpx"},{"code":"OCBC0229","active":false,"category":"fpx"},{"code":"BP-FKR01","active":true,"category":"billplz"},{"code":"BP-PPL01","active":true,"category":"paypal"},{"code":"BP-2C2P1","active":false,"category":"2c2p"},{"code":"BP-OCBC1","active":true,"category":"ocbc"}]}';

    $faker = $this->expectRequest('GET', 'payment_gateways')
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->uses('PaymentGateway')->all();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});
