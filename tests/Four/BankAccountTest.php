<?php

beforeEach(function (): void {
    $this->apiVersion = 'v3';
});

billplz_register_bank_account_tests();

it('can called via helper', function (): void {
    $bank = $this->makeClient()->bank('v4');

    expect($bank)->toBeInstanceOf('Billplz\Four\BankAccount');
    expect($bank)->toBeInstanceOf('Billplz\Three\BankAccount');
    expect($bank->getVersion())->toBe($this->proxyApiVersion ?? $this->apiVersion);
});
