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
    public function it_can_check_account_registration()
    {
        $expected = '{"verified":true}';

        $request = $this->expectRequest('GET', 'check/bank_account_number/jomlaunch')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($request->http())
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

        $request = $this->expectRequest('GET', 'fpx_banks')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($request->http())
                        ->uses('Bank')
                        ->supportedForFpx();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }
}
