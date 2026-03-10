<?php

beforeEach(function (): void {
    $this->apiVersion = 'v3';
});

billplz_register_bill_tests();

it('can called via helper', function (): void {
    $bill = $this->makeClient()->bill('v3');

    expect($bill)->toBeInstanceOf('Billplz\Three\Bill');
    expect($bill->getVersion())->toBe('v3');
});
