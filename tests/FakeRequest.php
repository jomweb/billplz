<?php

namespace Billplz\TestCase;

use Mockery as m;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;
use Http\Client\Common\HttpMethodsClient;

class FakeRequest
{
    /**
     * HTTP client mock.
     *
     * @var \Mockery\MockeryInterface
     */
    protected $http;

    /**
     * Message mock.
     *
     * @var \Mockery\MockeryInterface
     */
    protected $message;

    /**
     * Expected URL endpoint.
     *
     * @var string
     */
    protected $expectedUrl;

    /**
     * Expected HTTP status code.
     *
     * @var int
     */
    protected $expectedStatusCode = 200;

    /**
     * Expected HTTP body.
     *
     * @var string
     */
    protected $expectedBody = '';

    /**
     * Construct a fake request.
     */
    public function __construct()
    {
        $this->http = m::mock(HttpMethodsClient::class);
        $this->message = m::mock(ResponseInterface::class);
    }

    /**
     * Create a fake request.
     *
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Set expected URL.
     *
     * @param  string  $expectedUrl
     *
     * @return $this
     */
    public function setExpectedUrl($expectedUrl)
    {
        $this->expectedUrl = $expectedUrl;

        return $this;
    }

    /**
     * Make expected HTTP request.
     *
     * @param  string $method
     * @param  array  $headers
     * @param  mixed $body
     *
     * @return $this
     */
    public function call($method = 'GET', array $headers = [], $body = '')
    {
        $this->http->shouldReceive('send')
                ->with($method, m::type(Uri::class), $headers, $body)
                ->andReturnUsing(function ($m, $u, $h, $b) {
                    Assert::assertSame((string) $u, $this->expectedUrl);

                    return $this->message;
                });

        return $this;
    }

    /**
     * Request should response with.
     *
     * @param  int  $code
     * @param  string  $body
     *
     * @return $this
     */
    public function shouldResponseWith($code = 200, $body = '')
    {
        $this->expectedStatusCode = $code;
        $this->expectedBody = $body;

        $this->message->shouldReceive('getStatusCode')->andReturn($code)
            ->shouldReceive('getBody')->andReturn($body);

        return $this;
    }

    /**
     * Get HTTP mock.
     *
     * @return \Mockery\MockeryInterface
     */
    public function http()
    {
        return $this->http;
    }

    /**
     * Get message mock.
     *
     * @return \Mockery\MockeryInterface
     */
    public function message()
    {
        return $this->message;
    }
}
