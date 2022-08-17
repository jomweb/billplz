<?php

namespace Billplz\Base;

use Billplz\Contracts\BankAccount as Contract;
use Billplz\Request;
use Laravie\Codex\Contracts\Response;

abstract class BankAccount extends Request implements Contract
{
    /**
     * Get A Bank Account.
     */
    public function get(string $accountNumber): Response
    {
        return $this->send('GET', "bank_verification_services/{$accountNumber}");
    }

    /**
     * Create A Bank Account.
     */
    public function create(
        string $name,
        string $identification,
        string $accountNumber,
        string $code,
        bool $organization
    ): Response {
        $body = compact('name', 'code', 'organization');
        $body['id_no'] = $identification;
        $body['acc_no'] = $accountNumber;

        return $this->send('POST', 'bank_verification_services', [], $body);
    }

    /**
     * Check Bank Account Number.
     */
    public function checkAccount(string $accountNumber): Response
    {
        return $this->send('GET', "check/bank_account_number/{$accountNumber}");
    }

    /**
     * Get list of bank for Bank Direct Feature.
     */
    public function supportedForFpx(): Response
    {
        return $this->send('GET', 'fpx_banks');
    }
}
