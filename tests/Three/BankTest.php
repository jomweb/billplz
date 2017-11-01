<?php

namespace Billplz\TestCase\Three;

use Billplz\Three\Bank;
use Laravie\Codex\Response;
use Billplz\TestCase\TestCase;

class BankTest extends TestCase
{
    /** @test */
    public function it_can_called_via_helper()
    {
        $bank = $this->makeClient()->bank();

        $this->assertInstanceOf('Billplz\Base\Bank', $bank);
        $this->assertInstanceOf('Billplz\Three\Bank', $bank);
        $this->assertSame('v3', $bank->getVersion());
    }

    /** @test */
    public function it_can_check_account_registration()
    {
        $expected = '{"verified":true}';

        $request = $this->expectRequest('GET', 'check/bank_account_number/jomlaunch')
                        ->shouldResponseWith(200, $expected);

        $response = $this->makeClient($request->http())
                        ->resource('Bank')
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
                        ->resource('Bank')
                        ->supportedForFpx();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($expected, $response->getBody());
    }
}
