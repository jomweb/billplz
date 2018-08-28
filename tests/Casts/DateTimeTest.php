<?php

namespace Billplz\TestCase\Casts;

use Billplz\Casts\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /** @test */
    public function it_can_cast_datetime_to_string()
    {
        $cast = new DateTime();

        $this->assertSame('2018-01-01', $cast->from(new \DateTime('2018-01-01 11:00:01')));
    }

    /** @test */
    public function it_wouldnt_cast_datetime_if_not_validated()
    {
        $cast = new DateTime();

        $this->assertSame('foo', $cast->from('foo'));
    }

    /** @test */
    public function it_can_cast_string_to_datetime()
    {
        $cast = new DateTime();

        $this->assertInstanceOf('DateTimeInterface', $cast->to('2018-01-01'));
    }

    /** @test */
    public function it_can_cast_none_string_to_datetime()
    {
        $cast = new DateTime();

        $this->assertNull($cast->to(null));
    }
}
