<?php

namespace Billplz\TestCase\Three;

use Billplz\TestCase\TestCase;
use \Laravie\Codex\Contracts\Response;

class PaymentMethodTest extends TestCase
{
    /**
     * API Version.
     *
     * @var string
     */
    protected $apiVersion = 'v3';
    
    /** @test */
    public function it_can_get_payment_methods()
    {
        $expected = '{"payment_methods":[{"code": "paypal","name": "PAYPAL","active": true},{"code": "fpx","name": "Online Banking","active": false}]}';

        $faker = $this->expectRequest('GET', 'collections/0idsxnh5/payment_methods')->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)->uses('PaymentMethod')->show('0idsxnh5');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }
}
