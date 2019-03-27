<?php

namespace Billplz\Tests;

use Mockery as m;
use Billplz\Client;
use Laravie\Codex\Discovery;
use Laravie\Codex\Testing\Faker;
use PHPUnit\Framework\TestCase as PHPUnit;

class TestCase extends PHPUnit
{
    const API_KEY = '73eb57f0-7d4e-42b9-a544-aeac6e4b0f81';
    const X_SIGNATURE = 'billplz';

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
    protected function tearDown(): void
    {
        m::close();

        Discovery::flush();
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

        return Faker::create()
                    ->expectEndpointIs($endpoint)
                    ->call($method, $headers, http_build_query($body, null, '&'));
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
    protected function expectStreamRequest($method = 'GET', $uri = '/', array $headers = [], array $body = [])
    {
        $endpoint = sprintf(
            'https://%s@%s/%s/%s', '73eb57f0-7d4e-42b9-a544-aeac6e4b0f81', $this->apiEndpoint, $this->apiVersion, $uri
        );

        return Faker::create()
                    ->expectEndpointIs($endpoint)
                    ->stream($method, $headers, $body);
    }

    /**
     * Create a fake client.
     *
     * @param  \Laravie\Codex\Testing\FakeRequest|null  $faker
     *
     * @return \Billplz\Client
     */
    protected function makeClient(Faker $faker = null): Client
    {
        if (is_null($faker)) {
            $faker = Faker::create();
        }

        $client = new Client($faker->http(), static::API_KEY, static::X_SIGNATURE);

        if (! is_null($this->apiVersion)) {
            $client->useVersion($this->apiVersion);
        }

        return $client;
    }
}
