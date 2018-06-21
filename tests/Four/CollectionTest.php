<?php

namespace Billplz\TestCase\Four;

use Billplz\TestCase\Base\CollectionTestCase;

class CollectionTest extends CollectionTestCase
{
    /**
     * API Version.
     *
     * @var string
     */
    protected $apiVersion = 'v4';

    /** @test */
    public function it_can_called_via_helper()
    {
        $collection = $this->makeClient()->collection('v4');

        $this->assertInstanceOf('Billplz\Four\Collection', $collection);
        $this->assertSame('v4', $collection->getVersion());
    }

    /** @test */
    public function it_can_retrieve_mass_payment_instance()
    {
        $massPayment = $this->makeClient()->collection('v4')->massPayment();

        $this->assertInstanceOf('Billplz\Four\Collection\MassPayment', $massPayment);
        $this->assertSame('v4', $massPayment->getVersion());
    }
}
