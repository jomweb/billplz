<?php

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;
use Http\Client\Common\HttpMethodsClient;
use Laravie\Codex\Common\Discovery as CommonDiscovery;
use Laravie\Codex\Common\Endpoint;
use Laravie\Codex\Common\Payload;
use Laravie\Codex\Common\Response as CodexResponse;
use Laravie\Codex\Concerns\Request\Multipart;
use Laravie\Codex\Contracts\Client as CodexClientContract;
use Laravie\Codex\Exceptions\HttpException;
use Laravie\Codex\Exceptions\UnauthorizedException;
use Laravie\Codex\Filter\Cast as CodexCast;
use Laravie\Codex\Filter\Sanitizer;
use Laravie\Codex\Testing\ArraySubset;
use Laravie\Codex\Testing\Assert as CodexAssert;
use Laravie\Codex\Testing\Faker;
use Mockery as m;
use Money\Money;
use Psr\Http\Message\ResponseInterface;

it('covers discovery helper flow', function (): void {
    CommonDiscovery::flush();

    $firstClient = CommonDiscovery::client();
    $overridden = Faker::create()->http();
    $overriddenBody = CommonDiscovery::override($overridden);

    expect($firstClient)->toBeInstanceOf(HttpMethodsClient::class);
    expect(CommonDiscovery::client())->toBe($overriddenBody);

    $refreshed = CommonDiscovery::refreshClient();

    expect($refreshed)->toBeInstanceOf(HttpMethodsClient::class);
    expect($refreshed)->not->toBe($overriddenBody);
});

it('covers endpoint composition, queries and method forwarding', function (): void {
    $endpoint = new Endpoint('https://example.com/base?foo=bar&baz=baz%20value', ['v1', 'users'], ['limit' => '20']);

    expect($endpoint->getUri())->toBe('https://example.com');
    expect($endpoint->getPath())->toBe(['base']);
    expect($endpoint->getQuery())->toMatchArray([
        'foo' => 'bar',
        'baz' => 'baz value/v1/users',
        'limit' => '20',
    ]);
    expect((string) $endpoint)->toContain('https://example.com/base');

    $hostless = new Endpoint('/relative/path');
    expect($hostless->getUri())->toBeNull();

    $endpoint->addQuery('status', 'ok');
    expect($endpoint->getQuery()['status'])->toBe('ok');
    expect($endpoint->withPath('/api'))->toBe($endpoint);
    expect((string) $endpoint)->toContain('/api');

    $nullPathEndpoint = new Endpoint(null, ['health']);
    expect((string) $nullPathEndpoint)->toBe('/health');

    expect(function () use ($endpoint): void {
        $endpoint->missingMethod();
    })
        ->toThrow(\BadMethodCallException::class);
});

it('covers payload and response payload branches', function (): void {
    $payload = new Payload(['name' => 'billplz']);
    $streamPayload = Payload::make(Utils::streamFor('streamed'));
    $same = Payload::make($payload);

    expect(Payload::make(['name' => 'billplz']))->toBeInstanceOf(Payload::class);
    expect($same)->toBe($payload);
    expect((string) $streamPayload->get())->toBe('streamed');
    expect($payload->get(['Content-Type' => 'application/json']))->toBe('{"name":"billplz"}');
    expect($payload->get())->toBe('name=billplz');
});

it('covers request helper plumbing and response factory', function (): void {
    $message = m::mock(ResponseInterface::class);
    $message->shouldReceive('getBody')->andReturn(Utils::streamFor('{}'));
    $message->shouldReceive('getStatusCode')->andReturn(200, 200);
    $message->shouldReceive('getReasonPhrase')->andReturn('OK');

    $client = m::mock(\Laravie\Codex\Contracts\Client::class);
    $client->shouldReceive('getApiEndpoint')->andReturn('https://example.com');
    $client->shouldReceive('send')->twice()->andReturn($message);

    $request = new class extends \Laravie\Codex\Request
    {
        protected function responseWith(ResponseInterface $message): \Laravie\Codex\Contracts\Response
        {
            return new CodexResponse($message);
        }

        public function callSend(string $method, $path, array $headers = [], $body = []): \Laravie\Codex\Contracts\Response
        {
            return $this->send($method, $path, $headers, $body);
        }

        public function callMergeApiHeaders(array $headers = []): array
        {
            return $this->mergeApiHeaders($headers);
        }

        public function callMergeApiBody(array $body = []): array
        {
            return $this->mergeApiBody($body);
        }
    };

    $request->setClient($client);

    $response = $request->callSend('POST', 'status', ['X-Test' => '1'], ['sample' => 1]);
    expect($response)->toBeInstanceOf(CodexResponse::class);

    $endpointResponse = $request->callSend('GET', new Endpoint('https://example.com', ['v1']), ['X-Test' => '2']);
    expect($endpointResponse)->toBeInstanceOf(CodexResponse::class);

    expect($request->callMergeApiHeaders(['Accept' => 'json']))->toMatchArray(['Accept' => 'json']);
    expect($request->callMergeApiBody(['foo' => 'bar']))->toMatchArray(['foo' => 'bar']);
});

it('covers codex response success, failures and magic helpers', function (): void {
    $jsonResponse = m::mock(ResponseInterface::class);
    $jsonResponse->shouldReceive('getBody')->andReturn(Utils::streamFor('{"ok":true}'));
    $jsonResponse->shouldReceive('getStatusCode')->andReturn(200);
    $jsonResponse->shouldReceive('getReasonPhrase')->andReturn('OK');
    $jsonResponse->shouldReceive('getHeader')->andReturn([]);
    $jsonResponse->shouldReceive('getHeaderLine')->andReturn('');
    $response = new CodexResponse($jsonResponse);

    expect($response->isSuccessful())->toBeTrue();
    expect($response->isNotFound())->toBeFalse();
    expect($response->isUnauthorized())->toBeFalse();
    expect($response->toArray())->toBe(['ok' => true]);
    expect($response->validate())->toBe($response);

    $responseCode = null;
    $responseObj = null;
    $response->validateWith(function ($code, $value) use (&$responseCode, &$responseObj): void {
        $responseCode = $code;
        $responseObj = $value;
    });
    expect($responseCode)->toBe(200);
    expect($responseObj)->toBeInstanceOf(CodexResponse::class);

    $thenCode = null;
    $response->then(function ($value, $code) use (&$thenCode): void {
        $thenCode = $code;
        expect($value)->toBeInstanceOf(CodexResponse::class);
    });
    expect($thenCode)->toBe(200);

    $filteredResponse = new class($jsonResponse) extends CodexResponse
    {
        public function __construct(ResponseInterface $message)
        {
            parent::__construct($message);
        }

        public function toArray(): array
        {
            return ['filtered'];
        }
    };
    expect($filteredResponse->toArray())->toBe(['filtered']);
    expect($filteredResponse->getHeaderLine('RateLimit-Limit'))->toBe('');
    expect($filteredResponse->message)->toBe($jsonResponse);
    expect($filteredResponse->missing)->toBeNull();

    expect(fn () => $response->nope())->toThrow(\BadMethodCallException::class);
});

it('covers response exception branches', function (): void {
    $errorResponse = m::mock(ResponseInterface::class);
    $errorResponse->shouldReceive('getStatusCode')->andReturn(401);
    $errorResponse->shouldReceive('getReasonPhrase')->andReturn('Unauthorized');

    $successResponse = m::mock(ResponseInterface::class);
    $successResponse->shouldReceive('getStatusCode')->andReturn(200);
    $successResponse->shouldReceive('getReasonPhrase')->andReturn('OK');

    $httpException = new HttpException($errorResponse, 'unauthorized');
    expect($httpException->getStatusCode())->toBe(401);
    $httpException->setResponse($successResponse);
    expect($httpException->getStatusCode())->toBe(200);
    expect($httpException->getResponse())->toBe($successResponse);

    expect(fn () => $httpException->setResponse('invalid'))
        ->toThrow(\InvalidArgumentException::class);
});

it('covers codex sanitizer recursion and casting', function (): void {
    $cast = new class extends CodexCast
    {
        protected function isValid($value): bool
        {
            return is_numeric($value);
        }

        protected function fromCast($value)
        {
            return (int) $value;
        }

        protected function toCast($value)
        {
            return (string) $value;
        }
    };

    $sanitizer = new Sanitizer;
    $sanitizer->add(['user', 'age'], $cast);
    $sanitizer->add(['user', 'address', 'zip'], $cast);

    $filteredFrom = $sanitizer->from([
        'user' => [
            'age' => '12',
            'name' => 'Tester',
            'address' => ['zip' => '123', 'line' => 'abc'],
        ],
    ]);

    $filteredTo = $sanitizer->to([
        'user' => [
            'age' => 12,
            'name' => 'Tester',
            'address' => ['zip' => 123, 'line' => 'abc'],
        ],
    ]);

    expect($filteredFrom['user']['age'])->toBe(12);
    expect($filteredFrom['user']['address'])->toBe(['zip' => 123, 'line' => 'abc']);
    expect($filteredTo['user']['age'])->toBe('12');
    expect($filteredTo['user']['address'])->toBe(['zip' => '123', 'line' => 'abc']);
    expect($filteredTo['missing'] ?? null)->toBeNull();
});

it('covers array subset and assertion helpers', function (): void {
    $subset = new ArraySubset(['name' => 'billplz'], true);

    expect($subset->evaluate(['name' => 'billplz', 'version' => 1], '', true))->toBeTrue();
    expect($subset->evaluate(new \ArrayObject(['name' => 'billplz']), '', true))->toBeTrue();
    expect($subset->evaluate(new \ArrayIterator(['name' => 'other']), '', true))->toBeFalse();

    CodexAssert::assertArraySubset(['name' => 'billplz'], ['name' => 'billplz']);
    CodexAssert::assertArraySubset(['name' => 'billplz'], new \ArrayObject(['name' => 'billplz']), true);
    expect(fn () => CodexAssert::assertArraySubset(['name' => 'billplz'], ['name' => 'other']))
        ->toThrow(\PHPUnit\Framework\ExpectationFailedException::class);
});

it('covers faker response helper assertions and request helpers', function (): void {
    $getFaker = Faker::create();
    $getFaker->expectEndpointIs('https://example.com');
    $getFaker->send('GET', ['Accept' => 'json'], 'ignored');
    $getFaker->shouldResponseWith(200, '{"status":"ok"}', ['X-Single' => '1', 'X-Multi' => ['a', 'b']])->expectReasonPhraseIs('OK');

    $getFaker->http()->sendRequest(new Request('GET', 'https://example.com', ['Accept' => 'json']));

    expect($getFaker->message()->getHeader('X-Single'))->toBe(['1']);
    expect($getFaker->message()->getHeaderLine('X-Multi'))->toBe('a, b');

    $jsonFaker = Faker::create();
    $jsonFaker->expectEndpointIs('https://example.com/json');
    $jsonFaker->sendJson('POST', ['X-Type' => 'json'], ['foo' => 'bar'])->shouldResponseWithJson(201, '{}');
    $jsonFaker->http()->sendRequest(
        new Request('POST', 'https://example.com/json', ['X-Type' => 'json', 'Content-Type' => 'application/json'], '{"foo":"bar"}')
    );

    $streamFaker = Faker::create();
    $stream = Utils::streamFor('streamed-body');
    $streamFaker->expectEndpointIs('https://example.com/stream');
    $streamFaker->stream('PUT', ['X-Stream' => '1']);
    $streamFaker->http()->sendRequest(new Request('PUT', 'https://example.com/stream', ['X-Stream' => '1'], $stream));
    expect($streamFaker->message())->toBeInstanceOf(\Mockery\MockInterface::class);
});

it('covers client class helpers and error paths', function (): void {
    $client = $this->makeClient();
    $responseClient = $this->makeClient(Faker::create());

    expect($client->queries())->toBe([]);
    expect($client->getApiEndpoint())->toBe('https://www.billplz.com/api');
    expect($client->getApiVersion())->toBe('v4');
    expect($responseClient->queries())->toMatchArray([]);

    $client->useCustomApiEndpoint('https://sandbox.test/api');
    expect($client->getApiEndpoint())->toBe('https://sandbox.test/api');

    expect($client->useVersion('v3')->getApiVersion())->toBe('v3');
    expect(fn () => $client->useVersion('v9'))
        ->toThrow(\InvalidArgumentException::class);

    expect(fn () => $client->uses('InvalidService'))
        ->toThrow(\InvalidArgumentException::class);
});

it('covers multipart payload helper behavior', function (): void {
    $response = m::mock(ResponseInterface::class);
    $response->shouldReceive('getBody')->andReturn(Utils::streamFor('{}'));
    $response->shouldReceive('getStatusCode')->andReturn(200);
    $response->shouldReceive('getReasonPhrase')->andReturn('OK');

    $client = m::mock(\Laravie\Codex\Contracts\Client::class);
    $client->shouldReceive('getApiEndpoint')->andReturn('https://example.com');
    $client->shouldReceive('stream')->andReturn($response);

    $request = new class extends \Laravie\Codex\Request
    {
        use Multipart;

        protected function responseWith(ResponseInterface $message): \Laravie\Codex\Contracts\Response
        {
            return new CodexResponse($message);
        }

        public function callStream(string $method, $path, array $headers = [], $body = [], array $files = []): \Laravie\Codex\Contracts\Response
        {
            return $this->stream($method, $path, $headers, $body, $files);
        }

        public function callPrepareMultipartPayloads(array $headers = [], array $body = [], array $files = []): array
        {
            return $this->prepareMultipartRequestPayloads($headers, $body, $files);
        }
    };

    $request->setClient($client);

    $request->callStream('POST', 'stream', ['Content-Type' => 'multipart/form-data'], Utils::streamFor('stream-body'));

    $request->callPrepareMultipartPayloads([], ['name' => 'billplz']);
    $request->callPrepareMultipartPayloads(
        ['Content-Type' => 'multipart/form-data'],
        ['meta' => ['title' => 'test'], 'tag' => 'release'],
        ['file' => null]
    );

    expect($request->callStream('POST', new Endpoint('https://example.com/path', ['bills'])))->toBeInstanceOf(CodexResponse::class);
});

it('covers base bill string payment completion', function (): void {
    $payload = [
        'email' => 'api@billplz.com',
        'mobile' => null,
        'name' => 'Michael API V3',
        'amount' => 200,
        'description' => 'Maecenas eu placerat ante.',
        'collection_id' => 'inbmmepb',
    ];

    $expected = '{"id":"8X0Iyzaw","collection_id":"inbmmepb","paid":false,"state":"due","amount":200,"paid_amount":0,"due_at":"2015-3-9","email":"api@billplz.com","mobile":null,"name":"MICHAEL API V3","url":"https:\/\/www.billplz.com\/bills\/8X0Iyzaw","reference_1_label":"Reference 1","reference_1":null,"reference_2_label":"Reference 2","reference_2":null,"redirect_url":null,"callback_url":"http:\/\/example.com\/webhook\/","description":"Maecenas eu placerat ante."}';

    $faker = Faker::create();
    $faker->expectEndpointIs(sprintf(
        'https://%s@www.billplz.com/api/v3/bills',
        static::API_KEY
    ));
    $faker->stream('POST')
        ->shouldResponseWithJson(200, $expected);

    $response = $this->makeClient($faker)->uses('Bill')->create(
        $payload['collection_id'],
        $payload['email'],
        $payload['mobile'],
        $payload['name'],
        Money::MYR($payload['amount']),
        'http://example.com/webhook/',
        $payload['description'],
        ['redirect_url' => 'https://example.com/redirect/']
    );

    expect($response->getStatusCode())->toBe(200);
});

it('covers low-coverage compatibility branches', function (): void {
    $uri = new \GuzzleHttp\Psr7\Uri('https://example.com');
    $endpoint = new Endpoint($uri);
    $endpoint->withPath('/api');

    expect((string) $endpoint)->toContain('/api');
    expect((string) $endpoint)->toStartWith('https://example.com');
    expect($endpoint->getHost())->toBe('example.com');

    $missing = new Endpoint('https://example.com/legacy', ['v1']);
    expect((string) $missing)->toContain('/v1');

    $sanitizer = new class extends Sanitizer
    {
        public function callSetNestedValue(array $data, array $keys, $value): array
        {
            return $this->setNestedValue($data, $keys, $value);
        }

        public function callGetNestedValue(array $data, array $keys)
        {
            return $this->getNestedValue($data, $keys);
        }
    };

    expect($sanitizer->callSetNestedValue(['age' => 1], [], 2))->toBe(['age' => 1]);
    expect($sanitizer->callSetNestedValue([], [null], 1))->toBe([]);
    expect($sanitizer->callGetNestedValue([], ['missing']))->toBeNull();

    $subset = new ArraySubset(['id' => 1]);
    expect($subset->evaluate(['id' => 1], '', true))->toBeTrue();
    expect($subset->evaluate(new \ArrayObject(['id' => 1, 'name' => 'bill']), '', true))->toBeTrue();
    expect($subset->evaluate(new \ArrayIterator(['id' => 1]), '', true))->toBeTrue();
    expect(fn () => $subset->evaluate(['id' => 2]))
        ->toThrow(\PHPUnit\Framework\ExpectationFailedException::class);

    $headerFaker = Faker::create();
    $headerFaker->expectResponseHeaders([
        5 => 'invalid-key',
        'X-Multi' => ['a', 'b'],
        'X-Scalar' => 'one',
    ]);

    expect($headerFaker->message()->hasHeader('X-Multi'))->toBeTrue();
    expect($headerFaker->message()->getHeader('X-Multi'))->toBe(['a', 'b']);
    expect($headerFaker->message()->getHeaderLine('X-Multi'))->toBe('a, b');
    expect($headerFaker->message()->getHeaderLine('Unknown'))->toBe('');

    $contentFaker = Faker::create();
    $stream = Utils::streamFor('stream-body');
    $contentFaker
        ->expectEndpointIs('https://example.com/stream')
        ->stream('PUT', ['Content-Type' => 'text/plain'])
        ->shouldResponseWith(200, '{}');
    $contentFaker->http()->sendRequest(new Request('PUT', 'https://example.com/stream', ['Content-Type' => 'text/plain'], $stream));

    $client = m::mock(CodexClientContract::class);
    $client->shouldReceive('getApiEndpoint')->andReturn('https://api.example.com');

    $request = new class extends \Laravie\Codex\Request
    {
        protected function responseWith(ResponseInterface $message): \Laravie\Codex\Contracts\Response
        {
            return new CodexResponse($message);
        }

        public function callGetApiBody(): array
        {
            return $this->getApiBody();
        }
    };
    $request->setClient($client);

    expect($request->callGetApiBody())->toBe([]);

    $requestResponse = new class extends \Laravie\Codex\Request
    {
        public function callSend(string $method, $path, array $headers = [], $body = []): \Laravie\Codex\Contracts\Response
        {
            return $this->send($method, $path, $headers, $body);
        }
    };

    $responseMessage = m::mock(ResponseInterface::class);
    $responseMessage->shouldReceive('getBody')->andReturn(Utils::streamFor('{}'));
    $responseMessage->shouldReceive('getStatusCode')->andReturn(200);
    $responseMessage->shouldReceive('getReasonPhrase')->andReturn('OK');

    $responseClient = m::mock(CodexClientContract::class);
    $responseClient->shouldReceive('getApiEndpoint')->andReturn('https://api.example.com');
    $responseClient->shouldReceive('send')->andReturn($responseMessage);
    $requestResponse->setClient($responseClient);

    expect($requestResponse->callSend('GET', 'status'))->toBeInstanceOf(\Laravie\Codex\Contracts\Response::class);

    $bodyStream = Utils::streamFor('payload-body');
    $streamedCallFaker = Faker::create();
    $streamedCallFaker
        ->expectEndpointIs('https://example.com/stream-body')
        ->call('PUT', ['Content-Type' => 'text/plain'], $bodyStream);
    $streamedCallFaker->http()->sendRequest(new Request('PUT', 'https://example.com/stream-body', ['Content-Type' => 'text/plain'], $bodyStream));

    $contentTypeFaker = Faker::create();
    $contentTypeFaker->expectContentTypeIs('text/plain');
    $contentTypeFaker->message()->getHeaderLine('Content-Type');

    $client = $this->makeClient(Faker::create());
    $client->setApiKey('next-key');
    expect($client->getApiKey())->toBe('next-key');

    $successBody = m::mock(ResponseInterface::class);
    $successBody->shouldReceive('getBody')->andReturn(Utils::streamFor('"ok"'));
    $successBody->shouldReceive('getStatusCode')->andReturn(200);
    $successBody->shouldReceive('getReasonPhrase')->andReturn('OK');

    $successResponse = new CodexResponse($successBody);
    expect($successResponse->getReasonPhrase())->toBe('OK');
    expect($successResponse->toArray())->toBe([]);

    $unauthorizedBody = m::mock(ResponseInterface::class);
    $unauthorizedBody->shouldReceive('getStatusCode')->andReturn(401);
    $unauthorizedBody->shouldReceive('getReasonPhrase')->andReturn('Unauthorized');
    $unauthorizedBody->shouldReceive('getBody')->andReturn(Utils::streamFor('{}'));

    $forbiddenBody = m::mock(ResponseInterface::class);
    $forbiddenBody->shouldReceive('getStatusCode')->andReturn(403);
    $forbiddenBody->shouldReceive('getReasonPhrase')->andReturn('Forbidden');
    $forbiddenBody->shouldReceive('getBody')->andReturn(Utils::streamFor('{}'));

    $notFoundBody = m::mock(ResponseInterface::class);
    $notFoundBody->shouldReceive('getBody')->andReturn(Utils::streamFor('{}'));
    $notFoundBody->shouldReceive('getStatusCode')->andReturn(404, 404);
    $notFoundBody->shouldReceive('getReasonPhrase')->andReturn('Not Found');

    $httpFailureBody = m::mock(ResponseInterface::class);
    $httpFailureBody->shouldReceive('getBody')->andReturn(Utils::streamFor('{}'));
    $httpFailureBody->shouldReceive('getStatusCode')->andReturn(502);
    $httpFailureBody->shouldReceive('getReasonPhrase')->andReturn('Bad Gateway');

    expect(fn () => (new CodexResponse($unauthorizedBody))->abortIfRequestUnauthorized())
        ->toThrow(UnauthorizedException::class);

    expect(fn () => (new CodexResponse($forbiddenBody))->abortIfRequestUnauthorized())
        ->toThrow(UnauthorizedException::class);

    expect(fn () => (new CodexResponse($notFoundBody))->abortIfRequestNotFound())
        ->toThrow(\Laravie\Codex\Exceptions\NotFoundException::class);

    expect(fn () => (new CodexResponse($httpFailureBody))->abortIfRequestHasFailed())
        ->toThrow(HttpException::class);
});
