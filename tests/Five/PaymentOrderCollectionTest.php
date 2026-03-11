<?php

use Billplz\Checksum;
use Billplz\Tests\TestCase;
use Laravie\Codex\Contracts\Response;

beforeEach(function (): void {
    $this->apiVersion = 'v5';
});

it('resolves the correct version', function (): void {
    $paymentOrderCollection = $this->makeClient()->paymentOrderCollection();

    expect($paymentOrderCollection)->toBeInstanceOf('Billplz\Five\PaymentOrderCollection');
    expect($paymentOrderCollection)->toBeInstanceOf('Billplz\Contracts\PaymentOrderCollection');
    expect($paymentOrderCollection->getVersion())->toBe($this->proxyApiVersion ?? $this->apiVersion);
});

it('can create payment order collection', function (): void {
    $title = 'My First API Payment Order Collection';
    $epoch = 1700000000;
    $expected = '{"id":"8f4e331f-ac71-435e-a870-72fe520b4563","title":"My First API Payment Order Collection","callback_url":"https:\/\/example.com\/payment-orders\/callback"}';

    $payload = [
        'title' => $title,
        'epoch' => $epoch,
        'checksum' => Checksum::create(TestCase::X_SIGNATURE, [
            $title,
            'https://example.com/payment-orders/callback',
            $epoch,
        ]),
        'callback_url' => 'https://example.com/payment-orders/callback',
    ];

    $faker = $this->expectRequest('POST', 'payment_order_collections', [], $payload)
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->paymentOrderCollection()->create($title, [
        'callback_url' => 'https://example.com/payment-orders/callback',
    ], $epoch);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});

it('can get payment order collection', function (): void {
    $paymentOrderCollectionId = '8f4e331f-ac71-435e-a870-72fe520b4563';
    $epoch = 1700000000;
    $expected = '{"id":"8f4e331f-ac71-435e-a870-72fe520b4563","title":"My First API Payment Order Collection","callback_url":"https:\/\/example.com\/payment-orders\/callback"}';

    $payload = [
        'payment_order_collection_id' => $paymentOrderCollectionId,
        'epoch' => $epoch,
        'checksum' => Checksum::create(TestCase::X_SIGNATURE, [
            $paymentOrderCollectionId,
            $epoch,
        ]),
    ];

    $faker = $this->expectRequest(
        'GET',
        sprintf(
            'payment_order_collections/%s?%s',
            $paymentOrderCollectionId,
            http_build_query($payload, '', '&')
        )
    )
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->paymentOrderCollection()->get($paymentOrderCollectionId, $epoch);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});
