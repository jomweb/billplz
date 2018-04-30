<?php

namespace Billplz\Base;

use Billplz\Request;

abstract class Bank extends Request
{
    /**
     * Create A Bank Account.
     *
     * @param  string  $name
     * @param  int  $id_no
     * @param  int  $acc_no
     * @param  string  $code
     * @param  bool $organization
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function createAccount($name, $id_no, $acc_no, $code, $organization)
    {
        $body = compact('name', 'id_no', 'acc_no', 'code', 'organization');

        return $this->send('POST', 'bank_verification_services', [], $body);
    }

    /**
     * Check Bank Account Number.
     *
     * @param  string|int  $number
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function checkAccount($number)
    {
        return $this->send('GET', "check/bank_account_number/{$number}");
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
