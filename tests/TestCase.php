<?php

namespace Billplz\TestCase;

use Mockery as m;
use Billplz\Client;
use PHPUnit\Framework\TestCase as PHPUnit;

class TestCase extends PHPUnit
{
    /**
     * API Version.
     *
     * @var string
     */
    protected $apiEndpoint = 'www.billplz.com/api';

    /**
     * API Version.
     *
     * @var string
     */
    protected $apiVersion;

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
    protected function expectRequest($method = 'GET', $uri = '/', array $headers = [], array $body = [])
    {
        $endpoint = sprintf(
            'https://%s@%s/%s/%s', '73eb57f0-7d4e-42b9-a544-aeac6e4b0f81', $this->apiEndpoint, $this->apiVersion, $uri
        );

        return FakeRequest::create()
                    ->expectEndpointIs($endpoint)
                    ->call($method, $headers, http_build_query($body, null, '&'));
    }

    /**
     * Create a fake client.
     *
     * @param  object|null  $http
     *
     * @return \Billplz\Client
     */
    protected function makeClient($http = null)
    {
        if (is_null($http)) {
            $http = FakeRequest::create()->http();
        }

        return new Client($http, '73eb57f0-7d4e-42b9-a544-aeac6e4b0f81', 'billplz');
    }
}
