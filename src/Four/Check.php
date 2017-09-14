<?php

namespace Billplz\Three;

class Check extends Request
{
    /**
     * Check Bank Account Number.
     *
     * @param  string|int  $number
     *
     * @return \Laravie\Codex\Response
     */
    public function bankAccount($number)
    {
        return $this->send('GET', "check/bank_account_number/{$number}");
    }
}
