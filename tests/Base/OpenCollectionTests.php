<?php

use Laravie\Codex\Response;

function billplz_register_open_collection_tests(array $hooks = []): void
{
    billplz_register_tests([
        'has proper signature' => function (): void {
            $collection = $this->makeClient()->openCollection();

            expect($collection)->toBeInstanceOf('Billplz\Base\OpenCollection');
            expect($collection->getVersion())->toBe($this->apiVersion);
        },
        'can create collection' => function (): void {
            $payload = [
                'title' => 'My First API Collection',
                'description' => 'Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.',
                'amount' => 299,
            ];

            $expected = '{"id":"0pp87t_6","title":"My First API Collection","description":"Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.","reference_1_label":null,"reference_2_label":null,"email_link":null,"amount":299,"fixed_amount":true,"tax":null,"fixed_quantity":true,"payment_button":"pay","photo":["retina_url":null,"avatar_url":null],"split_payment":["email":null,"fixed_cut":null,"variable_cut":null],"url":"https://www.billplz.com/0pp87t_6"}';

            $faker = $this->expectStreamRequest('POST', 'open_collections', [], $payload)
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('OpenCollection')
                ->create($payload['title'], $payload['description'], $payload['amount']);

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can list collections' => function (): void {
            $expected = '{"collections":[{"id":"0pp87t_6","title":"My First API Collection","description":"Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.","reference_1_label":null,"reference_2_label":null,"email_link":null,"amount":299,"fixed_amount":true,"tax":null,"fixed_quantity":true,"payment_button":"pay","photo":["retina_url":null,"avatar_url":null],"split_payment":["email":null,"fixed_cut":null,"variable_cut":null],"url":"https://www.billplz.com/0pp87t_6"}],"page":1}';

            $faker = $this->expectRequest('GET', 'open_collections')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('OpenCollection')
                ->all();

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can show collection' => function (): void {
            $expected = '{"id":"0pp87t_6","title":"My First API Collection","description":"Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.","reference_1_label":null,"reference_2_label":null,"email_link":null,"amount":299,"fixed_amount":true,"tax":null,"fixed_quantity":true,"payment_button":"pay","photo":["retina_url":null,"avatar_url":null],"split_payment":["email":null,"fixed_cut":null,"variable_cut":null],"url":"https://www.billplz.com/0pp87t_6"}';

            $faker = $this->expectRequest('GET', 'open_collections/0pp87t_6')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('OpenCollection')
                ->get('0pp87t_6');

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
    ], $hooks);
}
