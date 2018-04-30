<?php

namespace Billplz\Base;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;

abstract class Bank extends Request
{
    /**
     * Create A Bank Account.
     *
     * @param  string  $name
     * @param  string  $identification
     * @param  string  $accountNumber
     * @param  string  $code
     * @param  bool $organization
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function createAccount(
        string $name,
        string $identification,
        string $accountNumber,
        string $code,
        bool $organization
    ): Response {
        $body = compact('name', 'code', 'organization');
        $body['id_no'] = $identification;
        $body['acc_no'] = $accountNumber;

        return $this->send('POST', "bank_verification_services", [], $body);
    }

    /**
     * Check Bank Account Number.
     *
     * @param  string|int  $number
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function checkAccount($number): Response
    {
        return $this->send('GET', "check/bank_account_number/{$number}");
    }

    /**
     * Get list of bank for Bank Direct Feature.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function supportedForFpx(): Response
    {
        return $this->send('GET', 'fpx_banks');
    }
}
