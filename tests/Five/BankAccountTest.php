<?php

namespace Billplz\Tests\Five;

use Billplz\Tests\Base\BankAccountTestCase;

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
        $bank = $this->makeClient()->bank('v5');

        $this->assertInstanceOf('Billplz\Five\BankAccount', $bank);
        $this->assertInstanceOf('Billplz\Four\BankAccount', $bank);
        $this->assertInstanceOf('Billplz\Three\BankAccount', $bank);
        $this->assertSame('v3', $bank->getVersion());
    }
}
