<?php

namespace Billplz\Tests\Four;

use Billplz\Tests\Base\CollectionTestCase;

class CollectionTest extends CollectionTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        $this->apiVersion = 'v4';
    }

    /** @test */
    public function it_can_activate_collection()
    {
        $this->apiVersion = 'v3';

        parent::it_can_activate_collection();
    }

    /** @test */
    public function it_can_deactivate_collection()
    {
        $this->apiVersion = 'v3';

        parent::it_can_deactivate_collection();
    }

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
