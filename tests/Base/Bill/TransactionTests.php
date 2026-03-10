<?php

use Laravie\Codex\Response;

function billplz_register_bill_transaction_tests(array $hooks = []): void
{
    billplz_register_tests([
        'has proper signature' => function (): void {
            $transaction = $this->makeClient()->transaction();

            expect($transaction)->toBeInstanceOf('Billplz\Base\Bill\Transaction');
            expect($transaction->getVersion())->toBe($this->apiVersion);
        },
        'can check bill transaction' => function (): void {
            $expected = '{"bill_id":"inbmmepb","transactions":[{"id":"60793D4707CD","status":"completed","completed_at":"2017-02-23T12:49:23.612+08:00","payment_channel":"FPX"},{"id":"28F3D3194138","status":"failed","completed_at":,"payment_channel":"FPX"}],"page":1}';

            $faker = $this->expectRequest('GET', 'bills/inbmmepb/transactions')
                ->shouldResponseWithJson(200, $expected);

            $response = $this->makeClient($faker)
                ->uses('Bill.Transaction')
                ->get('inbmmepb');

            expect($response)->toBeInstanceOf(Response::class);
            expect($response->getStatusCode())->toBe(200);
            expect($response->getBody())->toBe($expected);
            expect($response->rateLimit())->toBeNull();
            expect($response->remainingRateLimit())->toBeNull();
            expect($response->rateLimitNextReset())->toBe(0);
        },
    ], $hooks);
}
