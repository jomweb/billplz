<?php

beforeEach(function (): void {
    $this->apiVersion = 'v3';
});

billplz_register_bill_transaction_tests();

it('can called via helper', function (): void {
    $transaction = $this->makeClient()->transaction('v4');

    expect($transaction)->toBeInstanceOf('Billplz\Four\Bill\Transaction');
    expect($transaction->getVersion())->toBe('v3');
});
