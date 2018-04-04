<?php

namespace Billplz\TestCase\Base;

use Duit\MYR;
use Money\Money;
use Laravie\Codex\Response;
use Billplz\TestCase\TestCase;

abstract class BillTestCase extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $bill = $this->makeClient()->bill();

        $this->assertInstanceOf('Billplz\Base\Bill', $bill);
        $this->assertSame('v3', $bill->getVersion());
    }

    /** @test */
    public function it_can_be_created()
    {
        $data = [
            'email' => 'api@billplz.com',
            'mobile' => null,
            'name' => 'Michael API V3',
            'amount' => 200,
            'description' => 'Maecenas eu placerat ante.',
            'collection_id' => 'inbmmepb',
            'callback_url' => 'http://example.com/webhook/',
        ];

        $expected = '{"id":"8X0Iyzaw","collection_id":"inbmmepb","paid":false,"state":"due","amount":200,"paid_amount":0,"due_at":"2015-3-9","email":"api@billplz.com","mobile":null,"name":"MICHAEL API V3","url":"https:\/\/www.billplz.com\/bills\/8X0Iyzaw","reference_1_label":"Reference 1","reference_1":null,"reference_2_label":"Reference 2","reference_2":null,"redirect_url":null,"callback_url":"http:\/\/example.com\/webhook\/","description":"Maecenas eu placerat ante."}';

        $request = $this->expectRequest('POST', 'bills', [], $data)
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($request->http())
                        ->uses('Bill')
                        ->create(
                            $data['collection_id'],
                            $data['email'],
                            $data['mobile'],
                            $data['name'],
                            MYR::given($data['amount']),
                            $data['callback_url'],
                            $data['description']
                        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Either $email or $mobile should be present
     */
    public function it_cant_be_created_given_empty_email_and_mobile()
    {
        $data = [
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
                            $data['collection_id'],
                            $data['email'],
                            $data['mobile'],
                            $data['name'],
                            Money::MYR($data['amount']),
                            $data['callback_url'],
                            $data['description']
                        );
    }

    /** @test */
    public function it_can_show_existing_bill()
    {
        $expected = '{"id":"8X0Iyzaw","collection_id":"inbmmepb","paid":false,"state":"due","amount":200,"paid_amount":0,"due_at":"2020-12-31","email":"api@billplz.com","mobile":"+60112223333","name":"MICHAEL API V3","url":"https:\/\/www.billplz.com\/bills\/8X0Iyzaw","reference_1_label":"First Name","reference_1":"Jordan","reference_2_label":"Last Name","reference_2":"Michael","redirect_url":"http:\/\/example.com\/redirect\/","callback_url":"http:\/\/example.com\/webhook\/","description":"Maecenas eu placerat ante."}';

        $request = $this->expectRequest('GET', 'bills/8X0Iyzaw')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($request->http())
                        ->uses('Bill')
                        ->show('8X0Iyzaw');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());

        $bill = $response->toArray();

        $this->assertInstanceOf(MYR::class, $bill['amount']);
        $this->assertSame('inbmmepb', $bill['collection_id']);
    }

    /** @test */
    public function it_can_delete_existing_bill()
    {
        $expected = '[]';

        $request = $this->expectRequest('DELETE', 'bills/8X0Iyzaw')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($request->http())
                        ->uses('Bill')
                        ->destroy('8X0Iyzaw');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertSame([], $response->toArray());
    }

    /** @test */
    public function it_can_parse_redirect_data_with_signature()
    {
        $client = $this->makeClient();

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
        $this->assertSame('true', $bill['paid']);
        $this->assertInstanceOf('DateTime', $bill['paid_at']);
        $this->assertEquals(new \DateTimeZone('+08:00'), $bill['paid_at']->getTimezone());
    }

    /**
     * @test
     * @expectedException \Billplz\Exceptions\FailedSignatureVerification
     */
    public function it_can_detect_invalid_redirect_data_with_signature()
    {
        $client = $this->makeClient();

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
}
