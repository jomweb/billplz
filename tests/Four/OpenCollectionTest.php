<?php

beforeEach(function (): void {
    $this->apiVersion = 'v4';
});

billplz_register_open_collection_tests();

it('can called via helper', function (): void {
    $collection = $this->makeClient()->openCollection('v4');

    expect($collection)->toBeInstanceOf('Billplz\Four\OpenCollection');
    expect($collection->getVersion())->toBe('v4');
});
