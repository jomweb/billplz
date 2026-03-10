<?php

use Laravie\Codex\Contracts\Response;
use Money\Money;

beforeEach(function (): void {
    $this->apiVersion = 'v4';
});

billplz_register_bill_tests([
    'can be created' => function (): void {
        $this->proxyApiVersion = 'v3';
    },
    'can be created with url as array' => function (): void {
        $this->proxyApiVersion = 'v3';
    },
    'can show existing bill' => function (): void {
        $this->proxyApiVersion = 'v3';
    },
    'can show existing bill with unlimited request limiter' => function (): void {
        $this->proxyApiVersion = 'v3';
    },
    'cant show existing bill when exceed request limiter' => function (): void {
        $this->proxyApiVersion = 'v3';
    },
    'can delete existing bill' => function (): void {
        $this->proxyApiVersion = 'v3';
    },
    'can check bill transaction' => function (): void {
        $this->proxyApiVersion = 'v3';
    },
]);

it('can called via helper', function (): void {
    $bill = $this->makeClient()->bill('v4');

    expect($bill)->toBeInstanceOf('Billplz\Four\Bill');
    expect($bill)->toBeInstanceOf('Billplz\Base\Bill');
    expect($bill->getVersion())->toBe('v4');
});

it('can charge credit card via token', function (): void {
    $payload = [
        'card_id' => '8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6',
        'token' => '77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740',
    ];

    $expected = '{"amount":10000,"status":"success","reference_id":"15681981586116610","hash_value":"1b66606732d846192b0b6aa4b754b3c8addd59072fce4bdd066b5d631c31d5e8","message":"Payment was successful"}';

    $faker = $this->expectRequest('POST', 'bills/awyzmy0m/charge', [], $payload)
        ->shouldResponseWith(200, $expected);

    $response = $this->makeClient($faker)
        ->uses('Bill')
        ->charge('awyzmy0m', $payload['card_id'], $payload['token']);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);

    $bill = $response->toArray();

    expect($bill['amount'])->toBeInstanceOf(Money::class);
    expect($bill['amount']->getAmount())->toBe('10000');
    expect($bill['amount']->getCurrency()->getCode())->toBe('MYR');
    expect($bill['status'])->toBe('success');
    expect($bill['reference_id'])->toBe('15681981586116610');
    expect($bill['hash_value'])->toBe('1b66606732d846192b0b6aa4b754b3c8addd59072fce4bdd066b5d631c31d5e8');
    expect($bill['message'])->toBe('Payment was successful');
});
