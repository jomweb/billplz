<?php

namespace Billplz\TestCase\Three;

use Billplz\TestCase\Base\BankAccountTestCase;

class BankAccountTest extends BankAccountTestCase
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
        $bank = $this->makeClient()->bank('v3');

        $this->assertInstanceOf('Billplz\Three\BankAccount', $bank);
        $this->assertSame('v3', $bank->getVersion());
    }
}
