<?php

namespace Billplz\Tests\Three;

use Billplz\Response;
use Billplz\Tests\Base\CollectionTestCase;

class CollectionTest extends CollectionTestCase
{
    /**
     * API Version.
     *
     * @var string
     */
    protected $apiVersion = 'v3';

    /** @test */
    public function it_can_called_via_helper()
    {
        $collection = $this->makeClient()->collection('v3');

        $this->assertInstanceOf('Billplz\Three\Collection', $collection);
        $this->assertSame('v3', $collection->getVersion());
    }

    /** @test */
    public function it_can_create_collection_with_logo()
    {
        $payload = [
            'title' => 'My First API Collection',
        ];

        $optional = [
            'logo' => realpath(__DIR__.'/../files/logo.png'),
        ];

        $expected = '{"id":"inbmmepb","title":"My First V4 API Collection","logo":{"thumb_url":null,"avatar_url":null},"split_header":false,"split_payments":[]}';

        $faker = $this->expectStreamRequest('POST', 'collections', [], $payload)
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
            ->uses('Collection', 'v3')
            ->create($payload['title'], $optional);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }
}
