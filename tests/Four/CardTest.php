<?php

use Laravie\Codex\Contracts\Response;

beforeEach(function (): void {
    $this->apiVersion = 'v4';
});

it('resolves the correct version', function (): void {
    $card = $this->makeClient()->uses('Card', 'v4');

    expect($card)->toBeInstanceOf('Billplz\Four\Card');
    expect($card->getVersion())->toBe('v4');
});

it('has proper signature', function (): void {
    $card = $this->makeClient()->card();

    expect($card)->toBeInstanceOf('Billplz\Four\Card');
    expect($card->getVersion())->toBe($this->apiVersion);
});

it('can create a valid credit card', function (): void {
    $payload = [
        'name' => 'Michael',
        'email' => 'api@billplz.com',
        'cvv' => '100',
        'expiry' => '0521',
        'phone' => '60122345678',
        'card_number' => '5111111111111118',
    ];

    $expected = '{"id":"8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6","card_number":"xxxx1118","expiry":"0521","provider":"mastercard","token":"77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740","active":true}';

    $faker = $this->expectRequest('POST', 'cards', [], $payload)
        ->shouldResponseWith(200, $expected);

    $response = $this->makeClient($faker)
        ->uses('Card')
        ->create(
            $payload['name'],
            $payload['email'],
            $payload['phone'],
            $payload['card_number'],
            $payload['cvv'],
            $payload['expiry']
        );

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);

    $card = $response->toArray();

    expect($card['id'])->toBe('8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6');
    expect($card['card_number'])->toBe('xxxx1118');
    expect($card['expiry'])->toBe('0521');
    expect($card['provider'])->toBe('mastercard');
    expect($card['token'])->toBe('77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740');
    expect($card['active'])->toBeTrue();
});

it('can activate a credit card', function (): void {
    $cardId = '8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6';
    $payload = [
        'token' => '77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740',
        'active' => true,
    ];

    $expected = '{"id":"8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6","card_number":"xxxx1118","expiry":"0521","provider":"mastercard","token":"77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740","active":true}';

    $faker = $this->expectRequest('PUT', "cards/{$cardId}", [], $payload)
        ->shouldResponseWith(200, $expected);

    $response = $this->makeClient($faker)
        ->uses('Card')
        ->activate($cardId, $payload['token']);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);

    $card = $response->toArray();

    expect($card['id'])->toBe('8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6');
    expect($card['card_number'])->toBe('xxxx1118');
    expect($card['expiry'])->toBe('0521');
    expect($card['provider'])->toBe('mastercard');
    expect($card['token'])->toBe('77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740');
    expect($card['active'])->toBeTrue();
});

it('can deactivate a credit card', function (): void {
    $cardId = '8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6';
    $payload = [
        'token' => '77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740',
        'active' => false,
    ];

    $expected = '{"id":"8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6","card_number":"xxxx1118","expiry":"0521","provider":"mastercard","token":"77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740","active":false}';

    $faker = $this->expectRequest('PUT', "cards/{$cardId}", [], $payload)
        ->shouldResponseWith(200, $expected);

    $response = $this->makeClient($faker)
        ->uses('Card')
        ->deactivate($cardId, $payload['token']);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->getStatusCode())->toBe(200);
    expect($response->getBody())->toBe($expected);

    $card = $response->toArray();

    expect($card['id'])->toBe('8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6');
    expect($card['card_number'])->toBe('xxxx1118');
    expect($card['expiry'])->toBe('0521');
    expect($card['provider'])->toBe('mastercard');
    expect($card['token'])->toBe('77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740');
    expect($card['active'])->toBeFalse();
});
