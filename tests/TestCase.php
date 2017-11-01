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
     * API Version
     *
     * @var string
     */
    protected $apiEndpoint = 'www.billplz.com/api';

    /**
     * API Version
     *
     * @var string
     */
    protected $apiVersion = 'v3';

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /**
     * Fake HTTP Client.
     *
     * @return \Mockery\MockInterface
     */
    protected function fakeHttpClient()
    {
        return m::mock(HttpMethodsClient::class);
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
        $http = $this->fakeHttpClient();
        $message = m::mock(ResponseInterface::class);

        $http->shouldReceive('send')
            ->with($method, m::type(Uri::class), $headers, http_build_query($body, null, '&'))
            ->andReturnUsing(function ($m, $u, $h, $b) use ($uri, $message) {
                $this->assertEquals((string) $u, sprintf(
                    "https://%s@%s/%s/%s",
                    '73eb57f0-7d4e-42b9-a544-aeac6e4b0f81',
                    $this->apiEndpoint,
                    $this->apiVersion,
                    $uri
                ));

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
        return new Client($http, '73eb57f0-7d4e-42b9-a544-aeac6e4b0f81', 'billplz');
    }
}
