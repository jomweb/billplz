<?php

namespace Billplz\TestCase\Four\Bill;

use Billplz\TestCase\Base\Bill\TransactionTestCase;

class TransactionTest extends TransactionTestCase
{
    /**
     * API Version.
     *
     * @var string
     */
    protected $apiVersion = 'v3';

    /** @test */
    public function it_can_called_via_helper()
    {
        $transaction = $this->makeClient()->transaction('v4');

        $this->assertInstanceOf('Billplz\Four\Bill\Transaction', $transaction);
        $this->assertSame('v3', $transaction->getVersion());
    }
}
