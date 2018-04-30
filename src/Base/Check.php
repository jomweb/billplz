<?php

namespace Billplz\Base;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;

abstract class Check extends Request
{
    /**
     * Check Bank Account Number.
     *
     * @param  string|int  $number
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function bankAccount($number): Response
    {
        return $this->client->uses('Bank', $this->getVersion())
                    ->checkAccount($number);
    }
}
