<?php

use Billplz\Client;
use Billplz\Tests\TestCase;
use Laravie\Codex\Discovery;
use Laravie\Codex\Testing\Faker;

it('can be initiated directly', function (): void {
    $faker = Faker::create();

    $client = new Client($faker->http(), TestCase::API_KEY, TestCase::X_SIGNATURE);

    expect($client->getApiKey())->toBe(TestCase::API_KEY);
    expect($client->getSignatureKey())->toBe(TestCase::X_SIGNATURE);
    expect($client->getApiEndpoint())->toBe('https://www.billplz.com/api');
});

it('can be initiated via make', function (): void {
    $faker = Faker::create();

    Discovery::override($faker->http());

    $client = Client::make(TestCase::API_KEY, TestCase::X_SIGNATURE);

    expect($client->getApiKey())->toBe(TestCase::API_KEY);
    expect($client->getSignatureKey())->toBe(TestCase::X_SIGNATURE);
    expect($client->getApiEndpoint())->toBe('https://www.billplz.com/api');
});

it('can use sandbox endpoint', function (): void {
    $client = $this->makeClient();

    $client->useSandbox();

    expect($client->getApiEndpoint())->toBe('https://www.billplz-sandbox.com/api');
});

it('can retrieve collection instance', function (): void {
    $client = $this->makeClient();

    $collection = $client->collection('v3');

    expect($collection)->toBeInstanceOf('Billplz\Base\Collection');
    expect($collection)->toBeInstanceOf('Billplz\Three\Collection');
});

it('can retrieve open collection instance', function (): void {
    $client = $this->makeClient();

    $collection = $client->openCollection('v3');

    expect($collection)->toBeInstanceOf('Billplz\Base\OpenCollection');
    expect($collection)->toBeInstanceOf('Billplz\Three\OpenCollection');
});

it('can retrieve bill instance', function (): void {
    $client = $this->makeClient();

    $bill = $client->bill('v3');

    expect($bill)->toBeInstanceOf('Billplz\Base\Bill');
    expect($bill)->toBeInstanceOf('Billplz\Three\Bill');
});

it('can retrieve transaction instance', function (): void {
    $client = $this->makeClient();

    $transaction = $client->transaction('v3');

    expect($transaction)->toBeInstanceOf('Billplz\Base\Bill\Transaction');
    expect($transaction)->toBeInstanceOf('Billplz\Three\Bill\Transaction');
});

it('can retrieve payout collection instance', function (): void {
    $client = $this->makeClient();

    $payoutCollection = $client->payoutCollection('v4');

    expect($payoutCollection)->toBeInstanceOf('Billplz\Four\Collection\Payout');
    expect($payoutCollection)->toBeInstanceOf('Billplz\Contracts\Collection\Payout');
});

it('can retrieve payout instance', function (): void {
    $client = $this->makeClient();

    $payout = $client->payout('v4');

    expect($payout)->toBeInstanceOf('Billplz\Four\Payout');
    expect($payout)->toBeInstanceOf('Billplz\Contracts\Payout');
});

it('can retrieve payment order instance', function (): void {
    $client = $this->makeClient();

    $paymentOrder = $client->paymentOrder();

    expect($paymentOrder)->toBeInstanceOf('Billplz\Five\PaymentOrder');
    expect($paymentOrder)->toBeInstanceOf('Billplz\Contracts\PaymentOrder');
});

it('can retrieve payment order collection instance', function (): void {
    $client = $this->makeClient();

    $paymentOrderCollection = $client->paymentOrderCollection();

    expect($paymentOrderCollection)->toBeInstanceOf('Billplz\Five\PaymentOrderCollection');
    expect($paymentOrderCollection)->toBeInstanceOf('Billplz\Contracts\PaymentOrderCollection');
});

it('can retrieve bank instance', function (): void {
    $client = $this->makeClient();

    $bank = $client->bank('v3');

    expect($bank)->toBeInstanceOf('Billplz\Base\BankAccount');
    expect($bank)->toBeInstanceOf('Billplz\Three\BankAccount');
});
