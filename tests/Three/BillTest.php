<?php

namespace Billplz\TestCase\Three;

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
        $bill = $this->makeClient()->bill();

        $this->assertInstanceOf('Billplz\Three\Bill', $bill);
    }
}
