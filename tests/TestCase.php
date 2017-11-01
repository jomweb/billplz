<?php

namespace Billplz\TestCase;

use Mockery as m;
use Billplz\Client;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Http\Client\Common\HttpMethodsClient;
use PHPUnit\Framework\TestCase as PHPUnit;

class TestCase extends PHPUnit
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /**
     * Create a fake HTTP request.
     *
     * @param  string  $method
     * @param  array  $headers
     * @param  array  $body
     *
     * @return array
     */
    protected function fakeHttpRequest($method = 'GET', $uri, array $headers = [], array $body = [])
    {
        $http = m::mock(HttpMethodsClient::class);
        $message = m::mock(ResponseInterface::class);

        $http->shouldReceive('send')
            ->with($method, m::type(Uri::class), $headers, http_build_query($body, null, '&'))
            ->andReturnUsing(function ($m, $u, $h, $b) use ($uri, $message) {
                $this->assertEquals((string) $u, "https://jomweb@www.billplz.com/api/v3/{$uri}");

                return $message;
            });

        return [$http, $message];
    }

    /**
     * Create a fake client.
     *
     * @param  object  $http
     *
     * @return \Billplz\Client
     */
    protected function fakeClient($http)
    {
        return new Client($http, 'jomweb', 'billplz');
    }
}
