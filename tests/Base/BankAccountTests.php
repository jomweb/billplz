<?php

use Laravie\Codex\Response;

function billplz_register_bank_account_tests(array $hooks = []): void
{
    billplz_register_tests([
        'has proper signature' => function (): void {
            $bank = $this->makeClient()->bank();

            expect($bank)->toBeInstanceOf('Billplz\Base\BankAccount');
            expect($bank->getVersion())->toBe($this->proxyApiVersion ?? $this->apiVersion);
        },
        'can get a bank account' => function (): void {
            $bankAccountNumber = 1234567890;
            $expected = '{"name":"sara","id_no":"820909101001","acc_no":"1234567890","code":"MBBEMYKL","organization":false,"authorization_date":"2015-12-03","status":"pending","processed_at":null,"rejected_desc":null}';

            $faker = $this->expectRequest('GET', "bank_verification_services/{$bankAccountNumber}")
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('BankAccount')
                ->get($bankAccountNumber);

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can create bank account' => function (): void {
            $expected = '{"name":"Insan Jaya","id_no":"91234567890","acc_no":"999988887777","code":"MBBEMYKL","organization":true,"authorization_date":"2017-07-03","status":"pending","processed_at":null,"rejected_desc":null}';

            $data = [
                'name' => 'Insan Jaya',
                'code' => 'MBBEMYKL',
                'organization' => true,
                'id_no' => '91234567890',
                'acc_no' => '999988887777',
            ];

            $faker = $this->expectRequest('POST', 'bank_verification_services', [], $data)
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('BankAccount')
                ->create(
                    $data['name'],
                    $data['id_no'],
                    $data['acc_no'],
                    $data['code'],
                    $data['organization']
                );

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can check account registration' => function (): void {
            $expected = '{"verified":true}';

            $faker = $this->expectRequest('GET', 'check/bank_account_number/jomlaunch')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('BankAccount')
                ->checkAccount('jomlaunch');

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
        'can return supported fpx' => function (): void {
            $expected = '{"bank":[{"name":"PBB0233","active":true},{"name":"MBB0227","active":true},{"name":"MBB0228","active":true}]}';

            $faker = $this->expectRequest('GET', 'fpx_banks')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('BankAccount')
                ->supportedForFpx();

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
    ], $hooks);
}
