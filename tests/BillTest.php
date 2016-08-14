<?php

use Money\Money;
use Billplz\Bill;
use Billplz\Client;

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

        $client->shouldReceive('send')->once()->with('POST', 'bills', [], $data)->andReturnNull();

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
