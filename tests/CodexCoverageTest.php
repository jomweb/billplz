<?php

use Billplz\Request;
use Billplz\Response;
use Http\Client\Common\HttpMethodsClient;
use Laravie\Codex\Common\Discovery as CommonDiscovery;
use Laravie\Codex\Concerns\Passport;
use Laravie\Codex\Concerns\Request\Json;
use Laravie\Codex\Contracts\Response as ResponseContract;
use Laravie\Codex\Testing\Assert as CodexAssert;
use Laravie\Codex\Testing\Faker;
use Money\Money;

final class TestDiscovery extends CommonDiscovery
{
    public static int $makeCalls = 0;

    public static ?HttpMethodsClient $nextClient = null;

    public static function make(): HttpMethodsClient
    {
        self::$makeCalls++;

        return self::$nextClient ?? Faker::create()->http();
    }
}

it('adds the json content type header before sending requests', function (): void {
    $response = Mockery::mock(ResponseContract::class);

    $request = new class($response)
    {
        use Json;

        public array $sent = [];

        public function __construct(private ResponseContract $response) {}

        public function sendJsonRequest(string $method, $path, array $headers = [], $body = []): ResponseContract
        {
            return $this->sendJson($method, $path, $headers, $body);
        }

        protected function send(string $method, $path, array $headers = [], $body = []): ResponseContract
        {
            $this->sent = compact('method', 'path', 'headers', 'body');

            return $this->response;
        }
    };

    $result = $request->sendJsonRequest('POST', '/bills', ['Accept' => 'application/json'], ['name' => 'Test']);

    expect($result)->toBe($response);
    expect($request->sent)->toBe([
        'method' => 'POST',
        'path' => '/bills',
        'headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ],
        'body' => ['name' => 'Test'],
    ]);
});

it('stores passport credentials fluently', function (): void {
    $passport = new class
    {
        use Passport;
    };

    expect($passport->getClientId())->toBeNull();
    expect($passport->getClientSecret())->toBeNull();
    expect($passport->getAccessToken())->toBeNull();

    $result = $passport
        ->setClientId('client-id')
        ->setClientSecret('client-secret')
        ->setAccessToken('access-token');

    expect($result)->toBe($passport);
    expect($passport->getClientId())->toBe('client-id');
    expect($passport->getClientSecret())->toBe('client-secret');
    expect($passport->getAccessToken())->toBe('access-token');
});

it('caches refreshed and overridden discovery clients', function (): void {
    $first = Faker::create()->http();
    $second = Faker::create()->http();
    $override = Faker::create()->http();

    TestDiscovery::flush();
    TestDiscovery::$makeCalls = 0;
    TestDiscovery::$nextClient = $first;

    expect(TestDiscovery::client())->toBe($first);
    expect(TestDiscovery::client())->toBe($first);
    expect(TestDiscovery::$makeCalls)->toBe(1);

    TestDiscovery::$nextClient = $second;

    expect(TestDiscovery::refreshClient())->toBe($second);
    expect(TestDiscovery::$makeCalls)->toBe(2);

    expect(TestDiscovery::override($override))->toBe($override);
    expect(TestDiscovery::client())->toBe($override);

    TestDiscovery::flush();
    TestDiscovery::$nextClient = null;
});

it('asserts array subsets for arrays and array access values', function (): void {
    CodexAssert::assertArraySubset(['foo' => 'bar'], ['foo' => 'bar', 'baz' => 'qux']);
    CodexAssert::assertArraySubset(['foo' => 'bar'], new ArrayObject(['foo' => 'bar', 'baz' => 'qux']));

    expect(true)->toBeTrue();
});

it('rejects invalid values when asserting array subsets', function (): void {
    try {
        CodexAssert::assertArraySubset('invalid', []);
        $this->fail('Expected invalid subset assertion to throw.');
    } catch (InvalidArgumentException $e) {
        expect($e->getMessage())->toContain('array or ArrayAccess');
    }

    try {
        CodexAssert::assertArraySubset([], 'invalid');
        $this->fail('Expected invalid array assertion to throw.');
    } catch (InvalidArgumentException $e) {
        expect($e->getMessage())->toContain('array or ArrayAccess');
    }
});

it('filters request bodies and preserves endpoint queries', function (): void {
    $this->apiVersion = 'v5';

    $faker = $this->expectRequest(
        'POST',
        'payments?foo=bar',
        ['Accept' => 'application/json', 'X-Test' => '1'],
        ['amount' => 200, 'description' => 'Example']
    )->shouldResponseWithJson(200, '{}');

    $request = new class extends Request
    {
        public function __construct()
        {
            parent::__construct();

            $this->version = 'v5';
        }

        public function createPayment(array $payload): ResponseContract
        {
            return $this->send(
                'POST',
                self::to('/payments', [], ['foo' => 'bar']),
                $this->mergeApiHeaders(['X-Test' => '1']),
                $this->mergeApiBody($payload)
            );
        }

        protected function getApiHeaders(): array
        {
            return ['Accept' => 'application/json'];
        }

        protected function getApiBody(): array
        {
            return ['amount' => Money::MYR(200)];
        }
    };

    $request->setClient($this->makeClient($faker));

    expect($request->hasFilterable())->toBeTrue();
    expect($request->createPayment(['description' => 'Example']))->toBeInstanceOf(Response::class);
});
