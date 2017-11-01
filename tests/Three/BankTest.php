<?php

namespace Billplz\TestCase\Three;

use Billplz\TestCase\Base\BankTestCase;

class BankTest extends BankTestCase
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
        $bank = $this->makeClient()->bank();

        $this->assertInstanceOf('Billplz\Three\Bank', $bank);
    }
}
