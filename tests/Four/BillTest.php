<?php

namespace Billplz\Tests\Four;

use Duit\MYR;
use Billplz\Tests\Base\BillTestCase;
use Laravie\Codex\Contracts\Response;

class BillTest extends BillTestCase
{
    /**
     * API Version.
     *
     * @var string
     */
    protected $apiVersion = 'v4';

    /** @test */
    public function it_can_called_via_helper()
    {
        $bill = $this->makeClient()->bill('v4');

        $this->assertInstanceOf('Billplz\Four\Bill', $bill);
        $this->assertInstanceOf('Billplz\Base\Bill', $bill);
        $this->assertSame('v4', $bill->getVersion());
    }

    /** @test */
    public function it_can_be_created()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_be_created();
    }

    /** @test */
    public function it_can_be_created_with_url_as_array()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_be_created_with_url_as_array();
    }

    /** @test */
    public function it_can_show_existing_bill()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_show_existing_bill();
    }

    /** @test */
    public function it_can_show_existing_bill_with_unlimited_request_limiter()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_show_existing_bill_with_unlimited_request_limiter();
    }

    /** @test */
    public function it_cant_show_existing_bill_when_exceed_request_limiter()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_cant_show_existing_bill_when_exceed_request_limiter();
    }

    /** @test */
    public function it_can_delete_existing_bill()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_delete_existing_bill();
    }

    /** @test */
    public function it_can_check_bill_transaction()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_check_bill_transaction();
    }

    /** @test */
    public function it_can_charge_credit_card_via_token()
    {
        $payload = [
            'card_id' => '8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6',
            'token' => '77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740',
        ];

        $expected = '{"amount":10000,"status":"success","reference_id":"15681981586116610","hash_value":"1b66606732d846192b0b6aa4b754b3c8addd59072fce4bdd066b5d631c31d5e8","message":"Payment was successful"}';

        $faker = $this->expectRequest('POST', 'bills/awyzmy0m/charge', [], $payload)
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Bill')
                        ->charge('awyzmy0m', $payload['card_id'], $payload['token']);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());

        $bill = $response->toArray();

        $this->assertInstanceOf(MYR::class, $bill['amount']);
        $this->assertSame('100.00', $bill['amount']->amount());
        $this->assertSame('success', $bill['status']);
        $this->assertSame('15681981586116610', $bill['reference_id']);
        $this->assertSame('1b66606732d846192b0b6aa4b754b3c8addd59072fce4bdd066b5d631c31d5e8', $bill['hash_value']);
        $this->assertSame('Payment was successful', $bill['message']);
    }
}
