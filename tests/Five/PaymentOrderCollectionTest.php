<?php

namespace Billplz\Tests\Five;

use Billplz\Checksum;
use Billplz\Tests\TestCase;
use Laravie\Codex\Contracts\Response;

class PaymentOrderCollectionTest extends TestCase
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
        $paymentOrderCollection = $this->makeClient()->paymentOrderCollection();

        $this->assertInstanceOf('Billplz\Five\PaymentOrderCollection', $paymentOrderCollection);
        $this->assertInstanceOf('Billplz\Contracts\PaymentOrderCollection', $paymentOrderCollection);
        $this->assertSame($this->proxyApiVersion ?? $this->apiVersion, $paymentOrderCollection->getVersion());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_payment_order_collection()
    {
        $title = 'My First API Payment Order Collection';
        $epoch = time();
        $expected = '{"id":"8f4e331f-ac71-435e-a870-72fe520b4563","title":"My First API Payment Order Collection","callback_url":"https:\/\/example.com\/payment-orders\/callback"}';

        $payload = [
            'title' => $title,
            'epoch' => $epoch,
            'checksum' => Checksum::create(static::X_SIGNATURE, [
                $title,
                'https://example.com/payment-orders/callback',
                $epoch,
            ]),
            'callback_url' => 'https://example.com/payment-orders/callback',
        ];

        $faker = $this->expectRequest('POST', 'payment_order_collections', [], $payload)
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)->paymentOrderCollection()->create($title, [
            'callback_url' => 'https://example.com/payment-orders/callback',
        ]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_get_payment_order_collection()
    {
        $paymentOrderCollectionId = '8f4e331f-ac71-435e-a870-72fe520b4563';
        $epoch = time();
        $expected = '{"id":"8f4e331f-ac71-435e-a870-72fe520b4563","title":"My First API Payment Order Collection","callback_url":"https:\/\/example.com\/payment-orders\/callback"}';

        $payload = [
            'payment_order_collection_id' => $paymentOrderCollectionId,
            'epoch' => $epoch,
            'checksum' => Checksum::create(static::X_SIGNATURE, [
                $paymentOrderCollectionId,
                $epoch,
            ]),
        ];

        $faker = $this->expectRequest(
            'GET',
            sprintf(
                'payment_order_collections/%s?%s',
                $paymentOrderCollectionId,
                http_build_query($payload, '', '&')
            )
        )
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)->paymentOrderCollection()->get($paymentOrderCollectionId);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }
}
