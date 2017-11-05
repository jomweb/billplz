<?php

namespace Billplz\TestCase\Four;

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
        $bank = $this->makeClient()->bank('v4');

        $this->assertInstanceOf('Billplz\Four\Bank', $bank);
        $this->assertInstanceOf('Billplz\Three\Bank', $bank);
        $this->assertSame('v3', $bank->getVersion());
    }
}
