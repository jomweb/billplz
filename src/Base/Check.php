<?php

namespace Billplz\Base;

use Billplz\Request;

abstract class Check extends Request
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
        return $this->client->uses('Bank', $this->getVersion())
                    ->checkAccount($number);
    }
}
