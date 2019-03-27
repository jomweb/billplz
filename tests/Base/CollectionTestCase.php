<?php

namespace Billplz\Tests\Base;

use Laravie\Codex\Response;
use Billplz\Tests\TestCase;

abstract class CollectionTestCase extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $collection = $this->makeClient()->collection();

        $this->assertInstanceOf('Billplz\Base\Collection', $collection);
        $this->assertSame($this->apiVersion, $collection->getVersion());
    }

    /** @test */
    public function it_can_create_collection()
    {
        $payload = [
            'title' => 'My First API Collection',
        ];

        $expected = '{"id":"inbmmepb","title":"My First V4 API Collection","logo":{"thumb_url":null,"avatar_url":null},"split_header":false,"split_payments":[]}';

        $faker = $this->expectStreamRequest('POST', 'collections', [], $payload)
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Collection')
                        ->create($payload['title']);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }

    /** @test */
    public function it_can_list_collections()
    {
        $expected = '{"collections":[{"id":"inbmmepb","title":"My First API Collection","logo":{"thumb_url":null,"avatar_url":null},"split_payment":{"email":null,"fixed_cut":null,"variable_cut":null,"split_header":false},"status":"active"}],"page":1}';

        $faker = $this->expectRequest('GET', 'collections')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Collection')
                        ->all();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }

    /** @test */
    public function it_can_show_collection()
    {
        $expected = '{"id":"inbmmepb","title":"My First API Collection","logo":{"thumb_url":null,"avatar_url":null},"split_payment":{"email":null,"fixed_cut":null,"variable_cut":null,"split_header":false},"status":"active"}';

        $faker = $this->expectRequest('GET', 'collections/inbmmepb')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Collection')
                        ->get('inbmmepb');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }

    /** @test */
    public function it_can_activate_collection()
    {
        $expected = '{}';

        $faker = $this->expectRequest('POST', 'collections/inbmmepb/activate')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Collection')
                        ->activate('inbmmepb');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }

    /** @test */
    public function it_can_deactivate_collection()
    {
        $expected = '{}';

        $faker = $this->expectRequest('POST', 'collections/inbmmepb/deactivate')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Collection')
                        ->deactivate('inbmmepb');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }
}
