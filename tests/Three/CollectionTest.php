<?php

namespace Billplz\Tests\Three;

use Billplz\Tests\Base\CollectionTestCase;

class CollectionTest extends CollectionTestCase
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
        $collection = $this->makeClient()->collection('v3');

        $this->assertInstanceOf('Billplz\Three\Collection', $collection);
        $this->assertSame('v3', $collection->getVersion());
    }
}
