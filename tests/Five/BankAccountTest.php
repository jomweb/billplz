<?php

beforeEach(function (): void {
    $this->proxyApiVersion = 'v3';
});

billplz_register_bank_account_tests();

it('can called via helper', function (): void {
    $bank = $this->makeClient()->bank('v5');

    expect($bank)->toBeInstanceOf('Billplz\Five\BankAccount');
    expect($bank)->toBeInstanceOf('Billplz\Four\BankAccount');
    expect($bank)->toBeInstanceOf('Billplz\Three\BankAccount');
    expect($bank->getVersion())->toBe('v3');
});
