<?php

namespace Billplz\Tests\Four;

use Billplz\Tests\TestCase;
use Laravie\Codex\Contracts\Response;

class MassPaymentTest extends TestCase
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
        $payment = $this->makeClient()->uses('MassPayment', 'v4');

        $this->assertInstanceOf('Billplz\Four\MassPayment', $payment);
        $this->assertSame('v4', $payment->getVersion());
    }

    /** @test */
    public function it_can_get_mass_payment()
    {
        $expected = '{"id":"afae4bqf","mass_payment_instruction_collection_id":"4po8no8h","bank_code":"MBBEMYKL","bank_account_number":"820808062202123","identity_number":820808062202,"name":"Michael Yap","description":"Maecenas eu placerat ante.","email":"hello@billplz.com","status":"processing","notification":false,"recipient_notification":true,"total":"2000"}';

        $faker = $this->expectRequest('GET', 'mass_payment_instructions/afae4bqf')
                    ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)->uses('MassPayment')->get('afae4bqf');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_create_mass_payment()
    {
        $expected = '{"id":"afae4bqf","mass_payment_instruction_collection_id":"4po8no8h","bank_code":"MBBEMYKL","bank_account_number":"820808062202123","identity_number":820808062202,"name":"Michael Yap","description":"Maecenas eu placerat ante.","email":"hello@billplz.com","status":"processing","notification":false,"recipient_notification":true,"total":"2000"}';

        $payload = [
            'name' => 'Michael Yap',
            'description' => 'Maecenas eu placerat ante.',
            'total' => 2000,
            'mass_payment_instruction_collection_id' => '4po8no8h',
            'bank_code' => 'MBBEMYKL',
            'bank_account_number' => '820808062202123',
            'identity_number' => '820808062202',
        ];

        $faker = $this->expectRequest('POST', 'mass_payment_instructions', [], $payload)
                    ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)->uses('MassPayment')->create(
            '4po8no8h', 'MBBEMYKL', '820808062202123', '820808062202', 'Michael Yap', 'Maecenas eu placerat ante.', 2000
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }
}
