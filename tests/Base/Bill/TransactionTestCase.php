<?php

namespace Billplz\Tests\Base\Bill;

use Billplz\Tests\TestCase;
use Laravie\Codex\Response;

abstract class TransactionTestCase extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $transaction = $this->makeClient()->transaction();

        $this->assertInstanceOf('Billplz\Base\Bill\Transaction', $transaction);
        $this->assertSame($this->apiVersion, $transaction->getVersion());
    }

    /** @test */
    public function it_can_check_bill_transaction()
    {
        $expected = '{"bill_id":"inbmmepb","transactions":[{"id":"60793D4707CD","status":"completed","completed_at":"2017-02-23T12:49:23.612+08:00","payment_channel":"FPX"},{"id":"28F3D3194138","status":"failed","completed_at":,"payment_channel":"FPX"}],"page":1}';

        $faker = $this->expectRequest('GET', 'bills/inbmmepb/transactions')
                        ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Bill.Transaction')
                        ->get('inbmmepb');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }
}
