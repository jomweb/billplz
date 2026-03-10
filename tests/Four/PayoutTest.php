<?php

use Laravie\Codex\Contracts\Response;

beforeEach(function (): void {
    $this->apiVersion = 'v4';
});

it('resolves the correct version', function (): void {
    $payment = $this->makeClient()->uses('Payout', 'v4');

    expect($payment)->toBeInstanceOf('Billplz\Four\Payout');
    expect($payment->getVersion())->toBe($this->proxyApiVersion ?? $this->apiVersion);
});

it('can get mass payment', function (): void {
    $expected = '{"id":"afae4bqf","mass_payment_instruction_collection_id":"4po8no8h","bank_code":"MBBEMYKL","bank_account_number":"820808062202123","identity_number":820808062202,"name":"Michael Yap","description":"Maecenas eu placerat ante.","email":"hello@billplz.com","status":"processing","notification":false,"recipient_notification":true,"total":"2000"}';

    $faker = $this->expectRequest('GET', 'mass_payment_instructions/afae4bqf')
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->uses('Payout')->get('afae4bqf');

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});

it('can create mass payment', function (): void {
    $expected = '{"id":"afae4bqf","mass_payment_instruction_collection_id":"4po8no8h","bank_code":"MBBEMYKL","bank_account_number":"820808062202123","identity_number":820808062202,"name":"Michael Yap","description":"Maecenas eu placerat ante.","email":"hello@billplz.com","status":"processing","notification":false,"recipient_notification":true,"total":"2000"}';

    $payload = [
        'name' => 'Michael Yap',
        'description' => 'Maecenas eu placerat ante.',
        'total' => 2000,
        'mass_payment_instruction_collection_id' => '4po8no8h',
        'bank_code' => 'MBBEMYKL',
        'bank_account_number' => '820808062202123',
        'identity_number' => '820808062202',
    ];

    $faker = $this->expectRequest('POST', 'mass_payment_instructions', [], $payload)
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->uses('Payout')->create(
        '4po8no8h', 'MBBEMYKL', '820808062202123', '820808062202', 'Michael Yap', 'Maecenas eu placerat ante.', 2000
    );

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});
