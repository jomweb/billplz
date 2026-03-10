<?php

use Billplz\Response;

beforeEach(function (): void {
    $this->apiVersion = 'v3';
});

billplz_register_collection_tests();

it('can called via helper', function (): void {
    $collection = $this->makeClient()->collection('v3');

    expect($collection)->toBeInstanceOf('Billplz\Three\Collection');
    expect($collection->getVersion())->toBe('v3');
});

it('can create collection with logo', function (): void {
    $payload = [
        'title' => 'My First API Collection',
    ];

    $optional = [
        'logo' => realpath(__DIR__.'/../files/logo.png'),
    ];

    $expected = '{"id":"inbmmepb","title":"My First V4 API Collection","logo":{"thumb_url":null,"avatar_url":null},"split_header":false,"split_payments":[]}';

    $faker = $this->expectStreamRequest('POST', 'collections', [], $payload)
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)
        ->uses('Collection', 'v3')
        ->create($payload['title'], $optional);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);
    expect($response->rateLimit())->toBeNull();
    expect($response->remainingRateLimit())->toBeNull();
    expect($response->rateLimitNextReset())->toBe(0);
});
