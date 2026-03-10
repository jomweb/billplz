<?php

beforeEach(function (): void {
    $this->apiVersion = 'v4';
});

billplz_register_collection_tests([
    'can activate collection' => function (): void {
        $this->proxyApiVersion = 'v3';
    },
    'can deactivate collection' => function (): void {
        $this->proxyApiVersion = 'v3';
    },
]);

it('can called via helper', function (): void {
    $collection = $this->makeClient()->collection('v4');

    expect($collection)->toBeInstanceOf('Billplz\Four\Collection');
    expect($collection->getVersion())->toBe('v4');
});

it('can retrieve payout instance', function (): void {
    $massPayment = $this->makeClient()->collection('v4')->payout();

    expect($massPayment)->toBeInstanceOf('Billplz\Four\Collection\Payout');
    expect($massPayment->getVersion())->toBe('v4');
});
