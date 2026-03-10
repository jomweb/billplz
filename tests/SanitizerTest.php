<?php

namespace Billplz\Tests;

use Billplz\Sanitizer;
use PHPUnit\Framework\TestCase as PHPUnit;

class SanitizerTest extends PHPUnit
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_has_proper_signature()
    {
        $this->assertInstanceOf('Laravie\Codex\Filter\Sanitizer', new Sanitizer());
    }
}
