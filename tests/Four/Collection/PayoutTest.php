<?php

use Laravie\Codex\Contracts\Response;

beforeEach(function (): void {
    $this->apiVersion = 'v4';
});

it('resolves the correct version', function (): void {
    $payment = $this->makeClient()->uses('Collection.Payout', 'v4');

    expect($payment)->toBeInstanceOf('Billplz\Four\Collection\Payout');
    expect($payment->getVersion())->toBe('v4');
});

it('can get mass payment for collection', function (): void {
    $expected = '{"id":"4po8no8h","title":"My First API MPI Collection","mass_payment_instructions_count":"0","paid_amount":"0","status":"active"}';

    $faker = $this->expectRequest('GET', 'mass_payment_instruction_collections/4po8no8h')
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->uses('Collection.Payout')->get('4po8no8h');

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});

it('can create mass payment for collection', function (): void {
    $expected = '{"id":"4po8no8h","title":"My First API MPI Collection","mass_payment_instructions_count":"0","paid_amount":"0","status":"active"}';

    $faker = $this->expectRequest('POST', 'mass_payment_instruction_collections', [], ['title' => 'My First API MPI Collection'])
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->uses('Collection.Payout')->create('My First API MPI Collection');

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});
