<?php

namespace Billplz\Tests\Four;

use Billplz\Tests\TestCase;
use Laravie\Codex\Contracts\Response;

class WebhookTest extends TestCase
{
    /**
     * API Version.
     *
     * @var string
     */
    protected $apiVersion = 'v4';

    /** @test */
    public function it_resolve_the_correct_version()
    {
        $payment = $this->makeClient()->uses('Webhook', 'v4');

        $this->assertInstanceOf('Billplz\Four\Webhook', $payment);
        $this->assertSame('v4', $payment->getVersion());
    }

    /** @test */
    public function it_can_get_webhook_rank()
    {
        $expected = '{"rank":1.2}';

        $faker = $this->expectRequest('GET', 'webhook_rank')
                    ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)->uses('Webhook')->rank();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }
}
