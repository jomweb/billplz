<?php

use Billplz\Checksum;
use Billplz\Tests\TestCase;
use Laravie\Codex\Contracts\Response;

beforeEach(function (): void {
    $this->apiVersion = 'v5';
});

it('resolves the correct version', function (): void {
    $paymentOrder = $this->makeClient()->paymentOrder();

    expect($paymentOrder)->toBeInstanceOf('Billplz\Five\PaymentOrder');
    expect($paymentOrder)->toBeInstanceOf('Billplz\Contracts\PaymentOrder');
    expect($paymentOrder->getVersion())->toBe($this->proxyApiVersion ?? $this->apiVersion);
});

it('can create payment order', function (): void {
    $paymentOrderCollectionId = '8f4e331f-ac71-435e-a870-72fe520b4563';
    $bankAccountNumber = '543478924652';
    $total = 2000;
    $epoch = 1700000000;
    $expected = '{"id":"cc92738f-dfda-4969-91dc-22a44afc7e26","payment_order_collection_id":"8f4e331f-ac71-435e-a870-72fe520b4563","bank_code":"MBBEMYKL","bank_account_number":"543478924652","name":"Michael Yap","description":"Maecenas eu placerat ante.","total":"2000","status":"pending"}';

    $payload = [
        'payment_order_collection_id' => $paymentOrderCollectionId,
        'bank_code' => 'MBBEMYKL',
        'bank_account_number' => $bankAccountNumber,
        'name' => 'Michael Yap',
        'description' => 'Maecenas eu placerat ante.',
        'total' => $total,
        'epoch' => $epoch,
        'checksum' => Checksum::create(TestCase::X_SIGNATURE, [
            $paymentOrderCollectionId,
            $bankAccountNumber,
            $total,
            $epoch,
        ]),
    ];

    $faker = $this->expectRequest('POST', 'payment_orders', [], $payload)
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->paymentOrder()->create(
        $paymentOrderCollectionId,
        'MBBEMYKL',
        $bankAccountNumber,
        'Michael Yap',
        'Maecenas eu placerat ante.',
        $total,
        [],
        $epoch
    );

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});

it('can get payment order', function (): void {
    $paymentOrderId = 'cc92738f-dfda-4969-91dc-22a44afc7e26';
    $epoch = 1700000000;
    $expected = '{"id":"cc92738f-dfda-4969-91dc-22a44afc7e26","payment_order_collection_id":"8f4e331f-ac71-435e-a870-72fe520b4563","bank_code":"MBBEMYKL","bank_account_number":"543478924652","name":"Michael Yap","description":"Maecenas eu placerat ante.","total":"2000","status":"pending"}';

    $payload = [
        'payment_order_id' => $paymentOrderId,
        'epoch' => $epoch,
        'checksum' => Checksum::create(TestCase::X_SIGNATURE, [
            $paymentOrderId,
            $epoch,
        ]),
    ];

    $faker = $this->expectRequest(
        'GET',
        sprintf('payment_orders/%s?%s', $paymentOrderId, http_build_query($payload, '', '&'))
    )
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->paymentOrder()->get($paymentOrderId, $epoch);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});

it('can get payment order limit', function (): void {
    $epoch = 1700000000;
    $expected = '{"available_limit":"15000","currency":"MYR"}';

    $payload = [
        'epoch' => $epoch,
        'checksum' => Checksum::create(TestCase::X_SIGNATURE, [
            $epoch,
        ]),
    ];

    $faker = $this->expectRequest(
        'GET',
        sprintf('payment_order_limit?%s', http_build_query($payload, '', '&'))
    )
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->paymentOrder()->limit($epoch);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});
