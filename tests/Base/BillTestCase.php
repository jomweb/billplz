<?php

namespace Billplz\Tests\Base;

use Billplz\PaymentCompletion;
use Billplz\Tests\TestCase;
use Duit\MYR;
use Laravie\Codex\Exceptions\HttpException;
use Laravie\Codex\Response;

abstract class BillTestCase extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $bill = $this->makeClient()->bill();

        $this->assertInstanceOf('Billplz\Base\Bill', $bill);
        $this->assertSame($this->apiVersion, $bill->getVersion());
    }

    /** @test */
    public function it_can_be_created()
    {
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
                MYR::given($payload['amount']),
                new PaymentCompletion($payload['callback_url']),
                $payload['description']
            );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_be_created_with_url_as_array()
    {
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
                MYR::given($payload['amount']),
                new PaymentCompletion(
                    $payload['callback_url'],
                    $payload['redirect_url']
                ),
                $payload['description']
            );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_cant_be_created_given_empty_email_and_mobile()
    {
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

        $response = $this->makeClient()
            ->uses('Bill')
            ->create(
                $payload['collection_id'],
                $payload['email'],
                $payload['mobile'],
                $payload['name'],
                MYR::given($payload['amount']),
                new PaymentCompletion($payload['callback_url']),
                $payload['description']
            );
    }

    /** @test */
    public function it_can_show_existing_bill()
    {
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

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());

        $bill = $response->toArray();

        $this->assertInstanceOf(MYR::class, $bill['amount']);
        $this->assertSame('2.00', $bill['amount']->amount());
        $this->assertSame('inbmmepb', $bill['collection_id']);
        $this->assertSame(300, $response->rateLimit());
        $this->assertSame(299, $response->remainingRateLimit());
        $this->assertSame(899, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_show_existing_bill_with_unlimited_request_limiter()
    {
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

        $this->assertInstanceOf(MYR::class, $bill['amount']);
        $this->assertSame('2.00', $bill['amount']->amount());
        $this->assertSame('inbmmepb', $bill['collection_id']);
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_cant_show_existing_bill_when_exceed_request_limiter()
    {
        $this->expectException('Billplz\Exceptions\ExceedRequestLimits');

        $expected = '{"error":{"type":"RateLimit","message":"Too many requests"}}';

        $faker = $this->expectRequest('GET', 'bills/8X0Iyzaw')
            ->shouldResponseWithJson(429, $expected, [
                'RateLimit-Limit' => 300,
                'RateLimit-Remaining' => 0,
                'RateLimit-Reset' => 299,
            ]);

        try {
            $response = $this->makeClient($faker)
                ->uses('Bill')
                ->get('8X0Iyzaw');
        } catch (HttpException $e) {
            $this->assertSame(299, $e->timeRemaining());
            throw $e;
        }
    }

    /** @test */
    public function it_can_delete_existing_bill()
    {
        $expected = '[]';

        $faker = $this->expectRequest('DELETE', 'bills/8X0Iyzaw')
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
            ->uses('Bill')
            ->destroy('8X0Iyzaw');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertSame([], $response->toArray());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_check_bill_transaction()
    {
        $expected = '{"bill_id":"inbmmepb","transactions":[{"id":"60793D4707CD","status":"completed","completed_at":"2017-02-23T12:49:23.612+08:00","payment_channel":"FPX"},{"id":"28F3D3194138","status":"failed","completed_at":,"payment_channel":"FPX"}],"page":1}';

        $faker = $this->expectRequest('GET', 'bills/inbmmepb/transactions')
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
            ->uses('Bill')
            ->transaction('inbmmepb');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_parse_redirect_data_with_signature()
    {
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

        $this->assertSame('W_79pJDk', $bill['id']);
        $this->assertSame(true, $bill['paid']);
        $this->assertInstanceOf('DateTime', $bill['paid_at']);
        $this->assertEquals(new \DateTimeZone('+08:00'), $bill['paid_at']->getTimezone());
    }

    /** @test */
    public function it_can_parse_redirect_data_with_signature_and_extra_payment_completion_information()
    {
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

        $this->assertSame('W_79pJDk', $bill['id']);
        $this->assertSame(true, $bill['paid']);
        $this->assertInstanceOf('DateTime', $bill['paid_at']);
        $this->assertSame('AC4GC031F42H', $bill['transaction_id']);
        $this->assertSame('completed', $bill['transaction_status']);
        $this->assertEquals(new \DateTimeZone('+08:00'), $bill['paid_at']->getTimezone());
    }

    /** @test */
    public function it_can_parse_redirect_data_if_signature_key_is_not_configured()
    {
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

        $this->assertSame('W_79pJDk', $bill['id']);
        $this->assertSame(true, $bill['paid']);
        $this->assertInstanceOf('DateTime', $bill['paid_at']);
        $this->assertEquals(new \DateTimeZone('+08:00'), $bill['paid_at']->getTimezone());
    }

    /** @test */
    public function it_can_parse_redirect_data_with_extra_payment_completion_information_if_signature_key_is_not_configured()
    {
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

        $this->assertSame('W_79pJDk', $bill['id']);
        $this->assertSame(true, $bill['paid']);
        $this->assertInstanceOf('DateTime', $bill['paid_at']);
        $this->assertSame('AC4GC031F42H', $bill['transaction_id']);
        $this->assertSame('completed', $bill['transaction_status']);
        $this->assertEquals(new \DateTimeZone('+08:00'), $bill['paid_at']->getTimezone());
    }

    /** @test */
    public function it_cant_parse_redirect_data_without_given_signature()
    {
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

        $this->assertNull($bill);
    }

    /** @test */
    public function it_can_detect_invalid_redirect_data_with_signature()
    {
        $this->expectException('Billplz\Exceptions\FailedSignatureVerification');

        $payload = [
            'billplz' => [
                'id' => 'W_79pJDk',
                'paid' => 'false',
                'paid_at' => '2018-03-12 12:46:36 +0800',
                'x_signature' => 'a4ec01becf3b5f0221d1ad4a1296d77d1e9f8d3cc2d4404973d863983a25760f',
            ],
        ];

        $bill = $this->makeClient()
            ->setSignatureKey('foobar')
            ->uses('Bill')
            ->redirect($payload);
    }

    /** @test */
    public function it_can_detect_missing_redirect_data()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Expected $billplz to be an array!');

        $bill = $this->makeClient()
            ->setSignatureKey('foobar')
            ->uses('Bill')
            ->redirect([]);
    }

    /** @test */
    public function it_can_parse_webhook_data_with_signature()
    {
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

        $this->assertSame('W_79pJDk', $bill['id']);
        $this->assertSame(true, $bill['paid']);
        $this->assertInstanceOf('DateTime', $bill['paid_at']);
        $this->assertEquals(new \DateTimeZone('+08:00'), $bill['paid_at']->getTimezone());
    }

    /** @test */
    public function it_can_parse_webhook_data_with_signature_and_extra_payment_completion_information()
    {
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

        $this->assertSame('W_79pJDk', $bill['id']);
        $this->assertSame(true, $bill['paid']);
        $this->assertInstanceOf('DateTime', $bill['paid_at']);
        $this->assertSame('AC4GC031F42H', $bill['transaction_id']);
        $this->assertSame('completed', $bill['transaction_status']);
        $this->assertEquals(new \DateTimeZone('+08:00'), $bill['paid_at']->getTimezone());
    }

    /** @test */
    public function it_can_parse_webhook_data_if_signature_key_is_not_configured()
    {
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

        $this->assertSame('W_79pJDk', $bill['id']);
        $this->assertSame(true, $bill['paid']);
        $this->assertInstanceOf('DateTime', $bill['paid_at']);
        $this->assertEquals(new \DateTimeZone('+08:00'), $bill['paid_at']->getTimezone());
    }

    /** @test */
    public function it_can_parse_webhook_data_with_extra_payment_completion_information_if_signature_key_is_not_configured()
    {
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

        $this->assertSame('W_79pJDk', $bill['id']);
        $this->assertSame(true, $bill['paid']);
        $this->assertInstanceOf('DateTime', $bill['paid_at']);
        $this->assertSame('AC4GC031F42H', $bill['transaction_id']);
        $this->assertSame('completed', $bill['transaction_status']);
        $this->assertEquals(new \DateTimeZone('+08:00'), $bill['paid_at']->getTimezone());
    }

    /** @test */
    public function it_cant_parse_webhook_data_without_given_signature()
    {
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

        $this->assertNull($bill);
    }

    /** @test */
    public function it_can_detect_invalid_webhook_data_with_signature()
    {
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

        $bill = $this->makeClient()
            ->setSignatureKey('foobar')
            ->uses('Bill')
            ->webhook($payload);
    }
}
