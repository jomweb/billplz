<?php

namespace Billplz\Tests\Three\Bill;

use Billplz\Tests\Base\Bill\TransactionTestCase;

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
        $transaction = $this->makeClient()->transaction('v3');

        $this->assertInstanceOf('Billplz\Three\Bill\Transaction', $transaction);
        $this->assertSame('v3', $transaction->getVersion());
    }
}
