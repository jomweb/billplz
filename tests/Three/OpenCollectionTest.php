<?php

namespace Billplz\TestCase\Three;

use Billplz\TestCase\Base\OpenCollectionTestCase;

class OpenCollectionTest extends OpenCollectionTestCase
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
        $collection = $this->makeClient()->openCollection('v3');

        $this->assertInstanceOf('Billplz\Three\OpenCollection', $collection);
        $this->assertSame('v3', $collection->getVersion());
    }
}
