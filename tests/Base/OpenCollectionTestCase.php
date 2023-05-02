<?php

namespace Billplz\Tests\Base;

use Billplz\Tests\TestCase;
use Laravie\Codex\Response;

abstract class OpenCollectionTestCase extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $collection = $this->makeClient()->openCollection();

        $this->assertInstanceOf('Billplz\Base\OpenCollection', $collection);
        $this->assertSame($this->apiVersion, $collection->getVersion());
    }

    /** @test */
    public function it_can_create_collection()
    {
        $payload = [
            'title' => 'My First API Collection',
            'description' => 'Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.',
            'amount' => 299,
        ];

        $expected = '{"id":"0pp87t_6","title":"My First API Collection","description":"Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.","reference_1_label":null,"reference_2_label":null,"email_link":null,"amount":299,"fixed_amount":true,"tax":null,"fixed_quantity":true,"payment_button":"pay","photo":["retina_url":null,"avatar_url":null],"split_payment":["email":null,"fixed_cut":null,"variable_cut":null],"url":"https://www.billplz.com/0pp87t_6"}';

        $faker = $this->expectStreamRequest('POST', 'open_collections', [], $payload)
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
            ->uses('OpenCollection')
            ->create($payload['title'], $payload['description'], $payload['amount']);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_list_collections()
    {
        $expected = '{"collections":[{"id":"0pp87t_6","title":"My First API Collection","description":"Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.","reference_1_label":null,"reference_2_label":null,"email_link":null,"amount":299,"fixed_amount":true,"tax":null,"fixed_quantity":true,"payment_button":"pay","photo":["retina_url":null,"avatar_url":null],"split_payment":["email":null,"fixed_cut":null,"variable_cut":null],"url":"https://www.billplz.com/0pp87t_6"}],"page":1}';

        $faker = $this->expectRequest('GET', 'open_collections')
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
            ->uses('OpenCollection')
            ->all();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_show_collection()
    {
        $expected = '{"id":"0pp87t_6","title":"My First API Collection","description":"Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst.","reference_1_label":null,"reference_2_label":null,"email_link":null,"amount":299,"fixed_amount":true,"tax":null,"fixed_quantity":true,"payment_button":"pay","photo":["retina_url":null,"avatar_url":null],"split_payment":["email":null,"fixed_cut":null,"variable_cut":null],"url":"https://www.billplz.com/0pp87t_6"}';

        $faker = $this->expectRequest('GET', 'open_collections/0pp87t_6')
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
            ->uses('OpenCollection')
            ->get('0pp87t_6');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }
}
