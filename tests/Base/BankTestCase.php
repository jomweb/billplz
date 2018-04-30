<?php

namespace Billplz\TestCase\Base;

use Laravie\Codex\Response;
use Billplz\TestCase\TestCase;

abstract class BankTestCase extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $bank = $this->makeClient()->bank();

        $this->assertInstanceOf('Billplz\Base\Bank', $bank);
        $this->assertSame($this->apiVersion, $bank->getVersion());
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
                ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Bank')
                        ->createAccount(
                            $data['name'],
                            $data['id_no'],
                            $data['acc_no'],
                            $data['code'],
                            $data['organization']
                        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }

    /** @test */
    public function it_can_check_account_registration()
    {
        $expected = '{"verified":true}';

        $faker = $this->expectRequest('GET', 'check/bank_account_number/jomlaunch')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Bank')
                        ->checkAccount('jomlaunch');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }

    /** @test */
    public function it_can_return_supported_fpx()
    {
        $expected = '{"bank":[{"name":"PBB0233","active":true},{"name":"MBB0227","active":true},{"name":"MBB0228","active":true}]}';

        $faker = $this->expectRequest('GET', 'fpx_banks')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($faker)
                        ->uses('Bank')
                        ->supportedForFpx();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }
}
