<?php

namespace Billplz\Base;

use Billplz\Request;

abstract class Bank extends Request
{
    /**
     * Get A Bank Account.
     *
     * @param  string|int  $accountNumber
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get($accountNumber)
    {
        return $this->send('GET', "bank_verification_services/{$accountNumber}");
    }

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
    public function createAccount($name, $identification, $accountNumber, $code, $organization)
    {
        $body = compact('name', 'code', 'organization');
        $body['id_no'] = $identification;
        $body['acc_no'] = $accountNumber;

        return $this->send('POST', "bank_verification_services", [], $body);
    }

    /**
     * Check Bank Account Number.
     *
     * @param  string|int  $accountNumber
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function checkAccount($accountNumber)
    {
        return $this->send('GET', "check/bank_account_number/{$accountNumber}");
    }

    /**
     * Get list of bank for Bank Direct Feature.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function supportedForFpx()
    {
        return $this->send('GET', 'fpx_banks');
    }
}
