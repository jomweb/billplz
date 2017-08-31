<?php

namespace Billplz\TestCase\Three;

use Money\Money;
use Mockery as m;
use Billplz\Client;
use Laravie\Codex\Response;
use PHPUnit\Framework\TestCase;

class BillTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_can_be_created()
    {
        $http = m::mock('Http\Client\Common\HttpMethodsClient');
        $message = m::mock('Psr\Http\Message\ResponseInterface');

        $data = [
            'email' => 'api@billplz.com',
            'mobile' => null,
            'name' => 'Michael API V3',
            'amount' => 200,
            'description' => 'Maecenas eu placerat ante.',
            'collection_id' => 'inbmmepb',
            'callback_url' => 'http://example.com/webhook/',
        ];

        $http->shouldReceive('send')
            ->with('POST', m::type('GuzzleHttp\Psr7\Uri'), [], http_build_query($data, null, '&'))
            ->andReturn($message);

        $response = (new Client($http, 'foobar'))
                        ->resource('Bill')
                        ->create(
                            $data['collection_id'],
                            $data['email'],
                            $data['mobile'],
                            $data['name'],
                            Money::MYR($data['amount']),
                            $data['callback_url'],
                            $data['description']
                        );

        $this->assertInstanceOf(Response::class, $response);
    }
}
