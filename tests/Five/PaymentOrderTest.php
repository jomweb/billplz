<?php

namespace Billplz\Tests\Five;

use Billplz\Checksum;
use Billplz\Tests\TestCase;
use Laravie\Codex\Contracts\Response;

class PaymentOrderTest extends TestCase
{
    /**
     * API Version.
     *
     * @var string
     */
    protected $apiVersion = 'v5';

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_resolve_the_correct_version()
    {
        $paymentOrder = $this->makeClient()->paymentOrder();

        $this->assertInstanceOf('Billplz\Five\PaymentOrder', $paymentOrder);
        $this->assertInstanceOf('Billplz\Contracts\PaymentOrder', $paymentOrder);
        $this->assertSame($this->proxyApiVersion ?? $this->apiVersion, $paymentOrder->getVersion());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_payment_order()
    {
        $paymentOrderCollectionId = '8f4e331f-ac71-435e-a870-72fe520b4563';
        $bankAccountNumber = '543478924652';
        $total = 2000;
        $epoch = time();
        $expected = '{"id":"cc92738f-dfda-4969-91dc-22a44afc7e26","payment_order_collection_id":"8f4e331f-ac71-435e-a870-72fe520b4563","bank_code":"MBBEMYKL","bank_account_number":"543478924652","name":"Michael Yap","description":"Maecenas eu placerat ante.","total":"2000","status":"pending"}';

        $payload = [
            'payment_order_collection_id' => $paymentOrderCollectionId,
            'bank_code' => 'MBBEMYKL',
            'bank_account_number' => $bankAccountNumber,
            'name' => 'Michael Yap',
            'description' => 'Maecenas eu placerat ante.',
            'total' => $total,
            'epoch' => $epoch,
            'checksum' => Checksum::create(static::X_SIGNATURE, [
                $paymentOrderCollectionId,
                $bankAccountNumber,
                $total,
                $epoch,
            ]),
        ];

        $faker = $this->expectRequest('POST', 'payment_orders', [], $payload)
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)->paymentOrder()->create(
            $paymentOrderCollectionId,
            'MBBEMYKL',
            $bankAccountNumber,
            'Michael Yap',
            'Maecenas eu placerat ante.',
            $total
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_payment_order()
    {
        $paymentOrderId = 'cc92738f-dfda-4969-91dc-22a44afc7e26';
        $epoch = time();
        $expected = '{"id":"cc92738f-dfda-4969-91dc-22a44afc7e26","payment_order_collection_id":"8f4e331f-ac71-435e-a870-72fe520b4563","bank_code":"MBBEMYKL","bank_account_number":"543478924652","name":"Michael Yap","description":"Maecenas eu placerat ante.","total":"2000","status":"pending"}';

        $payload = [
            'payment_order_id' => $paymentOrderId,
            'epoch' => $epoch,
            'checksum' => Checksum::create(static::X_SIGNATURE, [
                $paymentOrderId,
                $epoch,
            ]),
        ];

        $faker = $this->expectRequest(
            'GET',
            sprintf('payment_orders/%s?%s', $paymentOrderId, http_build_query($payload, '', '&'))
        )
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)->paymentOrder()->get($paymentOrderId);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_payment_order_limit()
    {
        $epoch = time();
        $expected = '{"available_limit":"15000","currency":"MYR"}';

        $payload = [
            'epoch' => $epoch,
            'checksum' => Checksum::create(static::X_SIGNATURE, [
                $epoch,
            ]),
        ];

        $faker = $this->expectRequest(
            'GET',
            sprintf('payment_order_limit?%s', http_build_query($payload, '', '&'))
        )
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)->paymentOrder()->limit();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }
}
