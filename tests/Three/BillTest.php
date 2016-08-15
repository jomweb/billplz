<?php

use Money\Money;
use Billplz\Client;
use Billplz\Three\Bill;
use GuzzleHttp\Psr7\Uri;

class BillTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    public function bill_can_be_created()
    {
        $client = Mockery::mock(Client::class);

        $data = [
            'email' => 'api@billplz.com',
            'mobile' => null,
            'name' => 'Michael API V3',
            'amount' => 200,
            'description' => 'Maecenas eu placerat ante.',
            'collection_id' => 'inbmmepb',
            'callback_url' => 'http://example.com/webhook/',
        ];

        $client->shouldReceive('getApiEndpoint')->once()->andReturn('https://api.billplz.com')
            ->shouldReceive('getApiKey')->once()->andReturn('foobar')
            ->shouldReceive('send')->once()->with('POST', Mockery::type(Uri::class), [], $data)->andReturnNull();

        $bill = new Bill($client);

        $response = $bill->create(
            $data['collection_id'],
            $data['email'],
            $data['mobile'],
            $data['name'],
            Money::MYR($data['amount']),
            $data['callback_url'],
            $data['description']
        );

        $this->assertNull($response);
    }
}
