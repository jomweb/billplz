<?php

namespace Billplz\TestCase\Four;

use Billplz\TestCase\Base\BillTestCase;

class BillTest extends BillTestCase
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
        $bill = $this->makeClient()->bill('v4');

        $this->assertInstanceOf('Billplz\Four\Bill', $bill);
        $this->assertInstanceOf('Billplz\Three\Bill', $bill);
        $this->assertSame('v3', $bill->getVersion());
    }
}
