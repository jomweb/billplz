<?php

namespace Billplz\TestCase\Three;

use Billplz\Three\Check;
use Laravie\Codex\Response;
use Billplz\TestCase\TestCase;

class CheckTest extends TestCase
{

    /** @test */
    public function it_can_called_via_helper()
    {
        $check = $this->fakeClient($this->fakeHttpClient())->check();

        $this->assertInstanceOf('Billplz\Base\Check', $check);
        $this->assertInstanceOf('Billplz\Three\Check', $check);
        $this->assertSame('v3', $check->getVersion());
    }

    /** @test */
    public function it_can_check_account_registration()
    {
        list($http, $message) = $this->fakeHttpRequest('GET', 'check/bank_account_number/jomlaunch');

        $response = $this->fakeClient($http)
                        ->resource('Check')
                        ->bankAccount('jomlaunch');

        $this->assertInstanceOf(Response::class, $response);
    }
}
