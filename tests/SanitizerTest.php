<?php

namespace Billplz\TestCase;

use Billplz\Sanitizer;
use PHPUnit\Framework\TestCase;

class SanitizerTest extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $this->assertInstanceOf('Laravie\Codex\Sanitizer', new Sanitizer());
    }
}
