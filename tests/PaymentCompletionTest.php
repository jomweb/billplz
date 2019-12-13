<?php

namespace Billplz\Tests;

use Billplz\PaymentCompletion;
use PHPUnit\Framework\TestCase;

class PaymentCompletionTest extends TestCase
{
    /** @test */
    public function it_can_generate_without_redirect_url()
    {
        $payment = new PaymentCompletion('http://example.com/webhook/');

        $this->assertSame('http://example.com/webhook/', $payment->webhookUrl());
        $this->assertNull($payment->redirectUrl());
        $this->assertSame([
            'callback_url' => 'http://example.com/webhook/',
            'redirect_url' => null,
        ], $payment->toArray());
    }

    /** @test */
    public function it_can_generate_with_redirect_url()
    {
        $payment = new PaymentCompletion('http://example.com/webhook/', 'http://example.com/redirect/');

        $this->assertSame('http://example.com/webhook/', $payment->webhookUrl());
        $this->assertSame('http://example.com/redirect/', $payment->redirectUrl());
        $this->assertSame([
            'callback_url' => 'http://example.com/webhook/',
            'redirect_url' => 'http://example.com/redirect/',
        ], $payment->toArray());
    }
}
