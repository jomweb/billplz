<?php

namespace Billplz\TestCase\Base;

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
                        ->resource('Bill')
                        ->create(
                            $data['collection_id'],
                            $data['email'],
                            $data['mobile'],
                            $data['name'],
                            Money::MYR($data['amount']),
                            $data['callback_url'],
                            $data['description']
                        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }

    /** @test */
    public function it_can_show_existing_bill()
    {
        $expected = '{"id":"8X0Iyzaw","collection_id":"inbmmepb","paid":false,"state":"due","amount":200,"paid_amount":0,"due_at":"2020-12-31","email":"api@billplz.com","mobile":"+60112223333","name":"MICHAEL API V3","url":"https:\/\/www.billplz.com\/bills\/8X0Iyzaw","reference_1_label":"First Name","reference_1":"Jordan","reference_2_label":"Last Name","reference_2":"Michael","redirect_url":"http:\/\/example.com\/redirect\/","callback_url":"http:\/\/example.com\/webhook\/","description":"Maecenas eu placerat ante."}';

        $request = $this->expectRequest('GET', 'bills/8X0Iyzaw')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($request->http())
                        ->resource('Bill')
                        ->show('8X0Iyzaw');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());

        $bill = $response->toArray();

        $this->assertInstanceOf(Money::class, $bill['amount']);
        $this->assertSame('inbmmepb', $bill['collection_id']);
    }

    /** @test */
    public function it_can_delete_existing_bill()
    {
        $expected = '[]';

        $request = $this->expectRequest('DELETE', 'bills/8X0Iyzaw')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($request->http())
                        ->resource('Bill')
                        ->destroy('8X0Iyzaw');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertSame([], $response->toArray());
    }
}
