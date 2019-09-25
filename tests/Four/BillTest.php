<?php

namespace Billplz\Tests\Four;

use Billplz\Tests\Base\BillTestCase;

class BillTest extends BillTestCase
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
        $bill = $this->makeClient()->bill('v4');

        $this->assertInstanceOf('Billplz\Four\Bill', $bill);
        $this->assertInstanceOf('Billplz\Base\Bill', $bill);
        $this->assertSame('v4', $bill->getVersion());
    }

    /** @test */
    public function it_can_be_created()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_be_created();
    }

    /** @test */
    public function it_can_be_created_with_url_as_array()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_be_created_with_url_as_array();
    }

    /** @test */
    public function it_can_show_existing_bill()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_show_existing_bill();
    }

    /** @test */
    public function it_can_delete_existing_bill()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_delete_existing_bill();
    }

    /** @test */
    public function it_can_check_bill_transaction()
    {
        $this->proxyApiVersion = 'v3';

        parent::it_can_check_bill_transaction();
    }
}
