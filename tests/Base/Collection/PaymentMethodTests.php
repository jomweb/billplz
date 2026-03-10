<?php

use Laravie\Codex\Response;

function billplz_register_collection_payment_method_tests(array $hooks = []): void
{
    billplz_register_tests([
        'can get payment methods' => function (): void {
            $expected = '{"payment_methods":[{"code": "paypal","name": "PAYPAL","active": true},{"code": "fpx","name": "Online Banking","active": false}]}';

            $faker = $this->expectRequest('GET', 'collections/0idsxnh5/payment_methods')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)->uses('Collection.PaymentMethod')->get('0idsxnh5');

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can set payment methods' => function (): void {
            $expected = '{"payment_methods":[{"code": "paypal","name": "PAYPAL","active": true},{"code": "fpx","name": "Online Banking","active": true}]}';
            $payload = [
                ['code' => 'fpx'],
                ['code' => 'paypal'],
            ];

            $faker = $this->expectRequest('PUT', 'collections/0idsxnh5/payment_methods', [], $payload)
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)->uses('Collection.PaymentMethod')->update('0idsxnh5', ['fpx', 'paypal']);

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
    ], $hooks);
}
