<?php

namespace Billplz\Tests\Four;

use Billplz\Tests\TestCase;
use Laravie\Codex\Contracts\Response;

class CardTest extends TestCase
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
        $card = $this->makeClient()->uses('Card', 'v4');

        $this->assertInstanceOf('Billplz\Four\Card', $card);
        $this->assertSame('v4', $card->getVersion());
    }

    /** @test */
    public function it_has_proper_signature()
    {
        $card = $this->makeClient()->card();

        $this->assertInstanceOf('Billplz\Four\Card', $card);
        $this->assertSame($this->apiVersion, $card->getVersion());
    }

    /** @test */
    public function it_can_create_a_valid_credit_card()
    {
        $payload = [
            'name' => 'Michael',
            'email' => 'api@billplz.com',
            'cvv' => '100',
            'expiry' => '0521',
            'phone' => '60122345678',
            'card_number' => '5111111111111118',
        ];

        $expected = '{"id":"8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6","card_number":"xxxx1118","expiry":"0521","provider":"mastercard","token":"77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740","active":true}';

        $faker = $this->expectRequest('POST', 'cards', [], $payload)
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Card')
                        ->create(
                            $payload['name'],
                            $payload['email'],
                            $payload['phone'],
                            $payload['card_number'],
                            $payload['cvv'],
                            $payload['expiry']
                        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());


        $card = $response->toArray();

        $this->assertSame('8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6', $card['id']);
        $this->assertSame('xxxx1118', $card['card_number']);
        $this->assertSame('0521', $card['expiry']);
        $this->assertSame('mastercard', $card['provider']);
        $this->assertSame('77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740', $card['token']);
        $this->assertTrue($card['active']);
    }

    /** @test */
    public function it_can_activate_a_credit_card()
    {
        $cardId = '8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6';
        $payload = [
            'token' => '77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740',
            'active' => true,
        ];

        $expected = '{"id":"8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6","card_number":"xxxx1118","expiry":"0521","provider":"mastercard","token":"77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740","active":true}';

        $faker = $this->expectRequest('PUT', "cards/{$cardId}", [], $payload)
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Card')
                        ->activate($cardId, $payload['token']);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());


        $card = $response->toArray();

        $this->assertSame('8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6', $card['id']);
        $this->assertSame('xxxx1118', $card['card_number']);
        $this->assertSame('0521', $card['expiry']);
        $this->assertSame('mastercard', $card['provider']);
        $this->assertSame('77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740', $card['token']);
        $this->assertTrue($card['active']);
    }

     /** @test */
    public function it_can_deactivate_a_credit_card()
    {
        $cardId = '8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6';
        $payload = [
            'token' => '77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740',
            'active' => false,
        ];

        $expected = '{"id":"8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6","card_number":"xxxx1118","expiry":"0521","provider":"mastercard","token":"77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740","active":false}';

        $faker = $this->expectRequest('PUT', "cards/{$cardId}", [], $payload)
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Card')
                        ->deactivate($cardId, $payload['token']);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());


        $card = $response->toArray();

        $this->assertSame('8727fc3a-c04c-4c2b-9b67-947b5cfc2fb6', $card['id']);
        $this->assertSame('xxxx1118', $card['card_number']);
        $this->assertSame('0521', $card['expiry']);
        $this->assertSame('mastercard', $card['provider']);
        $this->assertSame('77d62ad5a3ae56aafc8e3529b89d0268afa205303f6017afbd9826afb8394740', $card['token']);
        $this->assertFalse($card['active']);
    }

}
