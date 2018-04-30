<?php

namespace Billplz\Base;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;

abstract class Check extends Request
{
    /**
     * Check Bank Account Number.
     *
     * @param  string  $accountNumber
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function bankAccount(string $accountNumber): Response
    {
        return $this->client->uses('Bank', $this->getVersion())
                    ->checkAccount($accountNumber);
    }
}
