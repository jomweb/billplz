<?php

namespace Billplz\TestCase;

use Billplz\Sanitizer;
use PHPUnit\Framework\TestCase as PHPUnit;

class SanitizerTest extends PHPUnit
{
    /** @test */
    public function it_has_proper_signature()
    {
        $this->assertInstanceOf('Laravie\Codex\Sanitizer', new Sanitizer());
    }
}
