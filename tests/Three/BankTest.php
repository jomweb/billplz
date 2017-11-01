<?php

namespace Billplz\TestCase\Three;

use Billplz\Three\Bank;
use Laravie\Codex\Response;
use Billplz\TestCase\TestCase;

class BankTest extends TestCase
{
    /** @test */
    public function it_can_check_account_registration()
    {
        list($http, $message) = $this->fakeHttpRequest('GET', 'check/bank_account_number/jomlaunch');

        $response = $this->fakeClient($http)
                        ->resource('Bank')
                        ->checkAccount('jomlaunch');

        $this->assertInstanceOf(Response::class, $response);
    }

    /** @test */
    public function it_can_return_supported_fpx()
    {
        list($http, $message) = $this->fakeHttpRequest('GET', 'fpx_banks');

        $response = $this->fakeClient($http)
                        ->resource('Bank')
                        ->supportedForFpx();

        $this->assertInstanceOf(Response::class, $response);
    }
}
