<?php

namespace Billplz\Tests\Base\Collection;

use Billplz\Tests\TestCase;
use Laravie\Codex\Response;

class PaymentMethodTestCase extends TestCase
{
    /** @test */
    public function it_can_get_payment_methods()
    {
        $expected = '{"payment_methods":[{"code": "paypal","name": "PAYPAL","active": true},{"code": "fpx","name": "Online Banking","active": false}]}';

        $faker = $this->expectRequest('GET', 'collections/0idsxnh5/payment_methods')
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)->uses('Collection.PaymentMethod')->get('0idsxnh5');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_set_payment_methods()
    {
        $expected = '{"payment_methods":[{"code": "paypal","name": "PAYPAL","active": true},{"code": "fpx","name": "Online Banking","active": true}]}';
        $payload = [
            ['code' => 'fpx'],
            ['code' => 'paypal'],
        ];

        $faker = $this->expectRequest('PUT', 'collections/0idsxnh5/payment_methods', [], $payload)
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)->uses('Collection.PaymentMethod')->update('0idsxnh5', ['fpx', 'paypal']);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }
}
