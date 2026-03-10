<?php

use Billplz\PaymentCompletion;
use Laravie\Codex\Exceptions\HttpException;
use Laravie\Codex\Response;
use Money\Money;

function billplz_register_bill_tests(array $hooks = []): void
{
    billplz_register_tests([
        'has proper signature' => function (): void {
            $bill = $this->makeClient()->bill();

            expect($bill)->toBeInstanceOf('Billplz\Base\Bill');
            expect($bill->getVersion())->toBe($this->apiVersion);
        },
        'can be created' => function (): void {
            $payload = [
                'email' => 'api@billplz.com',
                'mobile' => null,
                'name' => 'Michael API V3',
                'amount' => 200,
                'description' => 'Maecenas eu placerat ante.',
                'collection_id' => 'inbmmepb',
                'callback_url' => 'http://example.com/webhook/',
            ];

            $expected = '{"id":"8X0Iyzaw","collection_id":"inbmmepb","paid":false,"state":"due","amount":200,"paid_amount":0,"due_at":"2015-3-9","email":"api@billplz.com","mobile":null,"name":"MICHAEL API V3","url":"https:\/\/www.billplz.com\/bills\/8X0Iyzaw","reference_1_label":"Reference 1","reference_1":null,"reference_2_label":"Reference 2","reference_2":null,"redirect_url":null,"callback_url":"http:\/\/example.com\/webhook\/","description":"Maecenas eu placerat ante."}';

            $faker = $this->expectStreamRequest('POST', 'bills', [], $payload)
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('Bill')
                ->create(
                    $payload['collection_id'],
                    $payload['email'],
                    $payload['mobile'],
                    $payload['name'],
                    Money::MYR($payload['amount']),
                    new PaymentCompletion($payload['callback_url']),
                    $payload['description']
                );

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can be created with url as array' => function (): void {
            $payload = [
                'email' => 'api@billplz.com',
                'mobile' => null,
                'name' => 'Michael API V3',
                'amount' => 200,
                'description' => 'Maecenas eu placerat ante.',
                'collection_id' => 'inbmmepb',
                'callback_url' => 'http://example.com/webhook/',
                'redirect_url' => 'http://example.com/paid/',
            ];

            $expected = '{"id":"8X0Iyzaw","collection_id":"inbmmepb","paid":false,"state":"due","amount":200,"paid_amount":0,"due_at":"2015-3-9","email":"api@billplz.com","mobile":null,"name":"MICHAEL API V3","url":"https:\/\/www.billplz.com\/bills\/8X0Iyzaw","reference_1_label":"Reference 1","reference_1":null,"reference_2_label":"Reference 2","reference_2":null,"redirect_url":"http:\/\/example.com\/paid\/","callback_url":"http:\/\/example.com\/webhook\/","description":"Maecenas eu placerat ante."}';

            $faker = $this->expectStreamRequest('POST', 'bills', [], $payload)
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('Bill')
                ->create(
                    $payload['collection_id'],
                    $payload['email'],
                    $payload['mobile'],
                    $payload['name'],
                    Money::MYR($payload['amount']),
                    new PaymentCompletion($payload['callback_url'], $payload['redirect_url']),
                    $payload['description']
                );

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'cant be created given empty email and mobile' => function (): void {
            $this->expectException('InvalidArgumentException');
            $this->expectExceptionMessage('Either $email or $mobile should be present');

            $payload = [
                'email' => '',
                'mobile' => null,
                'name' => 'Michael API V3',
                'amount' => 200,
                'description' => 'Maecenas eu placerat ante.',
                'collection_id' => 'inbmmepb',
                'callback_url' => 'http://example.com/webhook/',
            ];

            $this->makeClient()
                ->uses('Bill')
                ->create(
                    $payload['collection_id'],
                    $payload['email'],
                    $payload['mobile'],
                    $payload['name'],
                    Money::MYR($payload['amount']),
                    new PaymentCompletion($payload['callback_url']),
                    $payload['description']
                );
        },
        'can show existing bill' => function (): void {
            $expected = '{"id":"8X0Iyzaw","collection_id":"inbmmepb","paid":false,"state":"due","amount":200,"paid_amount":0,"due_at":"2020-12-31","email":"api@billplz.com","mobile":"+60112223333","name":"MICHAEL API V3","url":"https:\/\/www.billplz.com\/bills\/8X0Iyzaw","reference_1_label":"First Name","reference_1":"Jordan","reference_2_label":"Last Name","reference_2":"Michael","redirect_url":"http:\/\/example.com\/redirect\/","callback_url":"http:\/\/example.com\/webhook\/","description":"Maecenas eu placerat ante."}';

            $faker = $this->expectRequest('GET', 'bills/8X0Iyzaw')
                ->shouldResponseWithJson(200, $expected, [
                    'RateLimit-Limit' => 300,
                    'RateLimit-Remaining' => 299,
                    'RateLimit-Reset' => 899,
                ]);

            $response = $this->makeClient($faker)
                ->uses('Bill')
                ->get('8X0Iyzaw');

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);

            $bill = $response->toArray();

            expect($bill['amount'])->toBeInstanceOf(Money::class);
            expect($bill['amount']->getAmount())->toBe('200');
            expect($bill['amount']->getCurrency()->getCode())->toBe('MYR');
            expect($bill['collection_id'])->toBe('inbmmepb');
            expect($response->rateLimit())->toBe(300);
            expect($response->remainingRateLimit())->toBe(299);
            expect($response->rateLimitNextReset())->toBe(899);
        },
        'can show existing bill with unlimited request limiter' => function (): void {
            $expected = '{"id":"8X0Iyzaw","collection_id":"inbmmepb","paid":false,"state":"due","amount":200,"paid_amount":0,"due_at":"2020-12-31","email":"api@billplz.com","mobile":"+60112223333","name":"MICHAEL API V3","url":"https:\/\/www.billplz.com\/bills\/8X0Iyzaw","reference_1_label":"First Name","reference_1":"Jordan","reference_2_label":"Last Name","reference_2":"Michael","redirect_url":"http:\/\/example.com\/redirect\/","callback_url":"http:\/\/example.com\/webhook\/","description":"Maecenas eu placerat ante."}';

            $faker = $this->expectRequest('GET', 'bills/8X0Iyzaw')
                ->shouldResponseWithJson(200, $expected, [
                    'RateLimit-Limit' => 'unlimited',
                    'RateLimit-Remaining' => 'unlimited',
                    'RateLimit-Reset' => 'unlimited',
                ]);

            $response = $this->makeClient($faker)
                ->uses('Bill')
                ->get('8X0Iyzaw');

            $bill = $response->toArray();

            expect($bill['amount'])->toBeInstanceOf(Money::class);
            expect($bill['amount']->getAmount())->toBe('200');
            expect($bill['amount']->getCurrency()->getCode())->toBe('MYR');
            expect($bill['collection_id'])->toBe('inbmmepb');
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'cant show existing bill when exceed request limiter' => function (): void {
            $this->expectException('Billplz\Exceptions\ExceedRequestLimits');

            $expected = '{"error":{"type":"RateLimit","message":"Too many requests"}}';

            $faker = $this->expectRequest('GET', 'bills/8X0Iyzaw')
                ->shouldResponseWithJson(429, $expected, [
                    'RateLimit-Limit' => 300,
                    'RateLimit-Remaining' => 0,
                    'RateLimit-Reset' => 299,
                ]);

            try {
                $this->makeClient($faker)
                    ->uses('Bill')
                    ->get('8X0Iyzaw');
            } catch (HttpException $exception) {
                expect($exception->timeRemaining())->toBe(299);

                throw $exception;
            }
        },
        'can delete existing bill' => function (): void {
            $expected = '[]';

            $faker = $this->expectRequest('DELETE', 'bills/8X0Iyzaw')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('Bill')
                ->destroy('8X0Iyzaw');

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->toArray())->toBe([]);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can check bill transaction' => function (): void {
            $expected = '{"bill_id":"inbmmepb","transactions":[{"id":"60793D4707CD","status":"completed","completed_at":"2017-02-23T12:49:23.612+08:00","payment_channel":"FPX"},{"id":"28F3D3194138","status":"failed","completed_at":,"payment_channel":"FPX"}],"page":1}';

            $faker = $this->expectRequest('GET', 'bills/inbmmepb/transactions')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('Bill')
                ->transaction('inbmmepb');

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can parse redirect data with signature' => function (): void {
            $payload = [
                'billplz' => [
                    'id' => 'W_79pJDk',
                    'paid' => 'true',
                    'paid_at' => '2018-03-12 12:46:36 +0800',
                    'x_signature' => 'a4ec01becf3b5f0221d1ad4a1296d77d1e9f8d3cc2d4404973d863983a25760f',
                ],
            ];

            $bill = $this->makeClient()
                ->setSignatureKey('foobar')
                ->uses('Bill')
                ->redirect($payload);

            expect($bill['id'])->toBe('W_79pJDk');
            expect($bill['paid'])->toBeTrue();
            expect($bill['paid_at'])->toBeInstanceOf('DateTime');
            expect($bill['paid_at']->getTimezone())->toEqual(new \DateTimeZone('+08:00'));
        },
        'can parse redirect data with signature and extra payment completion information' => function (): void {
            $payload = [
                'billplz' => [
                    'id' => 'W_79pJDk',
                    'paid' => 'true',
                    'paid_at' => '2018-03-12 12:46:36 +0800',
                    'transaction_id' => 'AC4GC031F42H',
                    'transaction_status' => 'completed',
                    'x_signature' => 'af43b15a12607f4965ae5bc03223c4bdcccc7d6f6e3535dda10451337eca78b7',
                ],
            ];

            $bill = $this->makeClient()
                ->setSignatureKey('foobar')
                ->uses('Bill')
                ->redirect($payload);

            expect($bill['id'])->toBe('W_79pJDk');
            expect($bill['paid'])->toBeTrue();
            expect($bill['paid_at'])->toBeInstanceOf('DateTime');
            expect($bill['transaction_id'])->toBe('AC4GC031F42H');
            expect($bill['transaction_status'])->toBe('completed');
            expect($bill['paid_at']->getTimezone())->toEqual(new \DateTimeZone('+08:00'));
        },
        'can parse redirect data if signature key is not configured' => function (): void {
            $payload = [
                'billplz' => [
                    'id' => 'W_79pJDk',
                    'paid' => 'true',
                    'paid_at' => '2018-03-12 12:46:36 +0800',
                    'x_signature' => 'a4ec01becf3b5f0221d1ad4a1296d77d1e9f8d3cc2d4404973d863983a25760f',
                ],
            ];

            $bill = $this->makeClient()
                ->setSignatureKey(null)
                ->uses('Bill')
                ->redirect($payload);

            expect($bill['id'])->toBe('W_79pJDk');
            expect($bill['paid'])->toBeTrue();
            expect($bill['paid_at'])->toBeInstanceOf('DateTime');
            expect($bill['paid_at']->getTimezone())->toEqual(new \DateTimeZone('+08:00'));
        },
        'can parse redirect data with extra payment completion information if signature key is not configured' => function (): void {
            $payload = [
                'billplz' => [
                    'id' => 'W_79pJDk',
                    'paid' => 'true',
                    'paid_at' => '2018-03-12 12:46:36 +0800',
                    'transaction_id' => 'AC4GC031F42H',
                    'transaction_status' => 'completed',
                    'x_signature' => 'af43b15a12607f4965ae5bc03223c4bdcccc7d6f6e3535dda10451337eca78b7',
                ],
            ];

            $bill = $this->makeClient()
                ->setSignatureKey(null)
                ->uses('Bill')
                ->redirect($payload);

            expect($bill['id'])->toBe('W_79pJDk');
            expect($bill['paid'])->toBeTrue();
            expect($bill['paid_at'])->toBeInstanceOf('DateTime');
            expect($bill['transaction_id'])->toBe('AC4GC031F42H');
            expect($bill['transaction_status'])->toBe('completed');
            expect($bill['paid_at']->getTimezone())->toEqual(new \DateTimeZone('+08:00'));
        },
        'cant parse redirect data without given signature' => function (): void {
            $payload = [
                'billplz' => [
                    'id' => 'W_79pJDk',
                    'paid' => 'true',
                    'paid_at' => '2018-03-12 12:46:36 +0800',
                ],
            ];

            $bill = $this->makeClient()
                ->setSignatureKey('foobar')
                ->uses('Bill')
                ->redirect($payload);

            expect($bill)->toBeNull();
        },
        'can detect invalid redirect data with signature' => function (): void {
            $this->expectException('Billplz\Exceptions\FailedSignatureVerification');

            $payload = [
                'billplz' => [
                    'id' => 'W_79pJDk',
                    'paid' => 'false',
                    'paid_at' => '2018-03-12 12:46:36 +0800',
                    'x_signature' => 'a4ec01becf3b5f0221d1ad4a1296d77d1e9f8d3cc2d4404973d863983a25760f',
                ],
            ];

            $this->makeClient()
                ->setSignatureKey('foobar')
                ->uses('Bill')
                ->redirect($payload);
        },
        'can detect missing redirect data' => function (): void {
            $this->expectException('InvalidArgumentException');
            $this->expectExceptionMessage('Expected $billplz to be an array!');

            $this->makeClient()
                ->setSignatureKey('foobar')
                ->uses('Bill')
                ->redirect([]);
        },
        'can parse webhook data with signature' => function (): void {
            $payload = [
                'id' => 'W_79pJDk',
                'collection_id' => '599',
                'paid' => 'true',
                'state' => 'paid',
                'amount' => '200',
                'paid_amount' => '0',
                'due_at' => '2020-12-31',
                'email' => 'api@billplz.com',
                'mobile' => '+60112223333',
                'name' => 'MICHAEL API',
                'url' => 'http://billplz.dev/bills/W_79pJDk',
                'paid_at' => '2015-03-09 16:23:59 +0800',
                'x_signature' => '01bdc1167f8b4dd1f591d8af7ada00061d39ca2b63e66c6588474a918a04796c',
            ];

            $bill = $this->makeClient()
                ->setSignatureKey('foobar')
                ->uses('Bill')
                ->webhook($payload);

            expect($bill['id'])->toBe('W_79pJDk');
            expect($bill['paid'])->toBeTrue();
            expect($bill['paid_at'])->toBeInstanceOf('DateTime');
            expect($bill['paid_at']->getTimezone())->toEqual(new \DateTimeZone('+08:00'));
        },
        'can parse webhook data with signature and extra payment completion information' => function (): void {
            $payload = [
                'id' => 'W_79pJDk',
                'collection_id' => '599',
                'paid' => 'true',
                'state' => 'paid',
                'amount' => '200',
                'paid_amount' => '0',
                'due_at' => '2020-12-31',
                'email' => 'api@billplz.com',
                'mobile' => '+60112223333',
                'name' => 'MICHAEL API',
                'url' => 'http://billplz.dev/bills/W_79pJDk',
                'paid_at' => '2015-03-09 16:23:59 +0800',
                'transaction_id' => 'AC4GC031F42H',
                'transaction_status' => 'completed',
                'x_signature' => 'c0041545dca8ceb082b29f544559465a0757b4208fe1ca74351128bc74402cf5',
            ];

            $bill = $this->makeClient()
                ->setSignatureKey('foobar')
                ->uses('Bill')
                ->webhook($payload);

            expect($bill['id'])->toBe('W_79pJDk');
            expect($bill['paid'])->toBeTrue();
            expect($bill['paid_at'])->toBeInstanceOf('DateTime');
            expect($bill['transaction_id'])->toBe('AC4GC031F42H');
            expect($bill['transaction_status'])->toBe('completed');
            expect($bill['paid_at']->getTimezone())->toEqual(new \DateTimeZone('+08:00'));
        },
        'can parse webhook data if signature key is not configured' => function (): void {
            $payload = [
                'id' => 'W_79pJDk',
                'collection_id' => '599',
                'paid' => 'true',
                'state' => 'paid',
                'amount' => '200',
                'paid_amount' => '0',
                'due_at' => '2020-12-31',
                'email' => 'api@billplz.com',
                'mobile' => '+60112223333',
                'name' => 'MICHAEL API',
                'url' => 'http://billplz.dev/bills/W_79pJDk',
                'paid_at' => '2015-03-09 16:23:59 +0800',
                'x_signature' => '01bdc1167f8b4dd1f591d8af7ada00061d39ca2b63e66c6588474a918a04796c',
            ];

            $bill = $this->makeClient()
                ->setSignatureKey(null)
                ->uses('Bill')
                ->webhook($payload);

            expect($bill['id'])->toBe('W_79pJDk');
            expect($bill['paid'])->toBeTrue();
            expect($bill['paid_at'])->toBeInstanceOf('DateTime');
            expect($bill['paid_at']->getTimezone())->toEqual(new \DateTimeZone('+08:00'));
        },
        'can parse webhook data with extra payment completion information if signature key is not configured' => function (): void {
            $payload = [
                'id' => 'W_79pJDk',
                'collection_id' => '599',
                'paid' => 'true',
                'state' => 'paid',
                'amount' => '200',
                'paid_amount' => '0',
                'due_at' => '2020-12-31',
                'email' => 'api@billplz.com',
                'mobile' => '+60112223333',
                'name' => 'MICHAEL API',
                'url' => 'http://billplz.dev/bills/W_79pJDk',
                'paid_at' => '2015-03-09 16:23:59 +0800',
                'transaction_id' => 'AC4GC031F42H',
                'transaction_status' => 'completed',
                'x_signature' => 'c0041545dca8ceb082b29f544559465a0757b4208fe1ca74351128bc74402cf5',
            ];

            $bill = $this->makeClient()
                ->setSignatureKey(null)
                ->uses('Bill')
                ->webhook($payload);

            expect($bill['id'])->toBe('W_79pJDk');
            expect($bill['paid'])->toBeTrue();
            expect($bill['paid_at'])->toBeInstanceOf('DateTime');
            expect($bill['transaction_id'])->toBe('AC4GC031F42H');
            expect($bill['transaction_status'])->toBe('completed');
            expect($bill['paid_at']->getTimezone())->toEqual(new \DateTimeZone('+08:00'));
        },
        'cant parse webhook data without given signature' => function (): void {
            $payload = [
                'id' => 'W_79pJDk',
                'collection_id' => '599',
                'paid' => 'true',
                'state' => 'paid',
                'amount' => '200',
                'paid_amount' => '0',
                'due_at' => '2020-12-31',
                'email' => 'api@billplz.com',
                'mobile' => '+60112223333',
                'name' => 'MICHAEL API',
                'url' => 'http://billplz.dev/bills/W_79pJDk',
                'paid_at' => '2015-03-09 16:23:59 +0800',
            ];

            $bill = $this->makeClient()
                ->setSignatureKey('foobar')
                ->uses('Bill')
                ->webhook($payload);

            expect($bill)->toBeNull();
        },
        'can detect invalid webhook data with signature' => function (): void {
            $this->expectException('Billplz\Exceptions\FailedSignatureVerification');

            $payload = [
                'id' => 'W_79pJDk',
                'collection_id' => '599',
                'paid' => 'true',
                'state' => 'paid',
                'amount' => '200',
                'paid_amount' => '0',
                'due_at' => '2020-12-31',
                'email' => 'api@billplz.com',
                'mobile' => '+60112223333',
                'name' => 'MICHAEL API',
                'url' => 'http://billplz.dev/bills/W_79pJDk',
                'paid_at' => '2015-03-09 16:23:59 +0800',
                'x_signature' => 'a4ec01becf3b5f0221d1ad4a1296d77d1e9f8d3cc2d4404973d863983a25760f',
            ];

            $this->makeClient()
                ->setSignatureKey('foobar')
                ->uses('Bill')
                ->webhook($payload);
        },
    ], $hooks);
}
