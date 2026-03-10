<?php

beforeEach(function (): void {
    $this->apiVersion = 'v3';
});

billplz_register_collection_payment_method_tests();

it('resolve the correct version', function (): void {
    $payment = $this->makeClient()->uses('Collection.PaymentMethod', 'v4');

    expect($payment)->toBeInstanceOf('Billplz\Three\Collection\PaymentMethod');
    expect($payment->getVersion())->toBe('v3');
});
