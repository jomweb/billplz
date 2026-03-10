<?php

beforeEach(function (): void {
    $this->apiVersion = 'v3';
});

billplz_register_open_collection_tests();

it('can called via helper', function (): void {
    $collection = $this->makeClient()->openCollection('v3');

    expect($collection)->toBeInstanceOf('Billplz\Three\OpenCollection');
    expect($collection->getVersion())->toBe('v3');
});
