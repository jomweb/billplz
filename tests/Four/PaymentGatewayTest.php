<?php

namespace Billplz\Tests\Four;

use Billplz\Tests\TestCase;
use Laravie\Codex\Contracts\Response;

class PaymentGatewayTest extends TestCase
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
        $payment = $this->makeClient()->uses('PaymentGateway', 'v4');

        $this->assertInstanceOf('Billplz\Four\PaymentGateway', $payment);
        $this->assertSame('v4', $payment->getVersion());
    }

    /** @test */
    public function it_can_get_payment_gateway_index()
    {
        $expected = '{"payment_gateways":[{"code":"MBU0227","active":true,"category":"fpx"},{"code":"OCBC0229","active":false,"category":"fpx"},{"code":"BP-FKR01","active":true,"category":"billplz"},{"code":"BP-PPL01","active":true,"category":"paypal"},{"code":"BP-2C2P1","active":false,"category":"2c2p"},{"code":"BP-OCBC1","active":true,"category":"ocbc"}]}';

        $faker = $this->expectRequest('GET', 'payment_gateways')
                    ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)->uses('PaymentGateway')->all();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }
}
