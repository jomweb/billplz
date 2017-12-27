<?php

namespace Billplz\Base\Bill;

use Billplz\Request;
use Laravie\Codex\Contracts\Response as ResponseContract;

abstract class Transaction extends Request
{
    /**
     * Show an existing bill transactions.
     *
     * @param  string  $id
     * @param  array   $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function show(string $id, array $optional = []): ResponseContract
    {
        return $this->send('GET', "bills/{$id}/transactions", [], $optional);
    }
}
