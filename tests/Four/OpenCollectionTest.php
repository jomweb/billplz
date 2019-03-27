<?php

namespace Billplz\Tests\Four;

use Billplz\Tests\Base\OpenCollectionTestCase;

class OpenCollectionTest extends OpenCollectionTestCase
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
        $collection = $this->makeClient()->openCollection('v4');

        $this->assertInstanceOf('Billplz\Four\OpenCollection', $collection);
        $this->assertSame('v4', $collection->getVersion());
    }
}
