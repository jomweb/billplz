<?php

namespace Billplz\Tests\Three\Collection;

use Billplz\Tests\Base\Collection\PaymentMethodTestCase;

class PaymentMethodTest extends PaymentMethodTestCase
{
    /**
     * API Version.
     *
     * @var string
     */
    protected $apiVersion = 'v3';

    /** @test */
    public function it_resolve_the_correct_version()
    {
        $payment = $this->makeClient()->uses('Collection.PaymentMethod', 'v3');

        $this->assertInstanceOf('Billplz\Three\Collection\PaymentMethod', $payment);
        $this->assertSame('v3', $payment->getVersion());
    }
}
