<?php

namespace Billplz\TestCase\Three;

use Billplz\TestCase\Base\CheckTestCase;

class CheckTest extends CheckTestCase
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
        $check = $this->makeClient()->check('v3');

        $this->assertInstanceOf('Billplz\Three\Check', $check);
        $this->assertSame('v3', $check->getVersion());
    }
}
