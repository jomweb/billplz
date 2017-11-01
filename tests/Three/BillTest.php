<?php

namespace Billplz\TestCase\Three;

use Money\Money;
use Laravie\Codex\Response;
use Billplz\TestCase\TestCase;

class BillTest extends TestCase
{
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

        list($http, $message) = $this->fakeHttpRequest('POST', 'bills', [], $data);

        $response = $this->fakeClient($http)
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
    }

    /** @test */
    public function it_can_show_existing_bill()
    {
        list($http, $message) = $this->fakeHttpRequest('GET', 'bills/8X0Iyzaw');

        $message->shouldReceive('getBody')->andReturn('{"id":"8X0Iyzaw","collection_id":"inbmmepb","paid":false,"state":"due","amount":200,"paid_amount":0,"due_at":"2020-12-31","email":"api@billplz.com","mobile":"+60112223333","name":"MICHAEL API V3","url":"https:\/\/www.billplz.com\/bills\/8X0Iyzaw","reference_1_label":"First Name","reference_1":"Jordan","reference_2_label":"Last Name","reference_2":"Michael","redirect_url":"http:\/\/example.com\/redirect\/","callback_url":"http:\/\/example.com\/webhook\/","description":"Maecenas eu placerat ante."}');

        $response = $this->fakeClient($http)
                        ->resource('Bill')
                        ->show('8X0Iyzaw');

        $bill = $response->toArray();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(Money::class, $bill['amount']);
        $this->assertSame('inbmmepb', $bill['collection_id']);
    }
}
