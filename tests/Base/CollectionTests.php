<?php

use Laravie\Codex\Response;

function billplz_register_collection_tests(array $hooks = []): void
{
    billplz_register_tests([
        'has proper signature' => function (): void {
            $collection = $this->makeClient()->collection();

            expect($collection)->toBeInstanceOf('Billplz\Base\Collection');
            expect($collection->getVersion())->toBe($this->apiVersion);
        },
        'can create collection' => function (): void {
            $payload = [
                'title' => 'My First API Collection',
            ];

            $expected = '{"id":"inbmmepb","title":"My First V4 API Collection","logo":{"thumb_url":null,"avatar_url":null},"split_header":false,"split_payments":[]}';

            $faker = $this->expectStreamRequest('POST', 'collections', [], $payload)
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('Collection')
                ->create($payload['title']);

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can list collections' => function (): void {
            $expected = '{"collections":[{"id":"inbmmepb","title":"My First API Collection","logo":{"thumb_url":null,"avatar_url":null},"split_payment":{"email":null,"fixed_cut":null,"variable_cut":null,"split_header":false},"status":"active"}],"page":1}';

            $faker = $this->expectRequest('GET', 'collections')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('Collection')
                ->all();

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can show collection' => function (): void {
            $expected = '{"id":"inbmmepb","title":"My First API Collection","logo":{"thumb_url":null,"avatar_url":null},"split_payment":{"email":null,"fixed_cut":null,"variable_cut":null,"split_header":false},"status":"active"}';

            $faker = $this->expectRequest('GET', 'collections/inbmmepb')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('Collection')
                ->get('inbmmepb');

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can activate collection' => function (): void {
            $expected = '{}';

            $faker = $this->expectRequest('POST', 'collections/inbmmepb/activate')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('Collection')
                ->activate('inbmmepb');

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can deactivate collection' => function (): void {
            $expected = '{}';

            $faker = $this->expectRequest('POST', 'collections/inbmmepb/deactivate')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('Collection')
                ->deactivate('inbmmepb');

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
    ], $hooks);
}
