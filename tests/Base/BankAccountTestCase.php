<?php

namespace Billplz\Tests\Base;

use Billplz\Tests\TestCase;
use Laravie\Codex\Response;

abstract class BankAccountTestCase extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $bank = $this->makeClient()->bank();

        $this->assertInstanceOf('Billplz\Base\BankAccount', $bank);
        $this->assertSame($this->proxyApiVersion ?? $this->apiVersion, $bank->getVersion());
    }

    /** @test */
    public function it_can_get_a_bank_account()
    {
        $bank_account_number = 1234567890;
        $expected = '{"name":"sara","id_no":"820909101001","acc_no":"1234567890","code":"MBBEMYKL","organization":false,"authorization_date":"2015-12-03","status":"pending","processed_at":null,"rejected_desc":null}';

        $faker = $this->expectRequest('GET', "bank_verification_services/{$bank_account_number}")
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
            ->uses('BankAccount')
            ->get($bank_account_number);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_create_bank_account()
    {
        $expected = '{"name":"Insan Jaya","id_no":"91234567890","acc_no":"999988887777","code":"MBBEMYKL","organization":true,"authorization_date":"2017-07-03","status":"pending","processed_at":null,"rejected_desc":null}';

        $data = [
            'name' => 'Insan Jaya',
            'code' => 'MBBEMYKL',
            'organization' => true,
            'id_no' => '91234567890',
            'acc_no' => '999988887777',
        ];

        $faker = $this->expectRequest('POST', 'bank_verification_services', [], $data)
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
            ->uses('BankAccount')
            ->create(
                $data['name'],
                $data['id_no'],
                $data['acc_no'],
                $data['code'],
                $data['organization']
            );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_check_account_registration()
    {
        $expected = '{"verified":true}';

        $faker = $this->expectRequest('GET', 'check/bank_account_number/jomlaunch')
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
            ->uses('BankAccount')
            ->checkAccount('jomlaunch');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }

    /** @test */
    public function it_can_return_supported_fpx()
    {
        $expected = '{"bank":[{"name":"PBB0233","active":true},{"name":"MBB0227","active":true},{"name":"MBB0228","active":true}]}';

        $faker = $this->expectRequest('GET', 'fpx_banks')
            ->shouldResponseWithJson(200, $expected);

        $response = $this->makeClient($faker)
            ->uses('BankAccount')
            ->supportedForFpx();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
        $this->assertNull($response->rateLimit());
        $this->assertNull($response->remainingRateLimit());
        $this->assertSame(0, $response->rateLimitNextReset());
    }
}
