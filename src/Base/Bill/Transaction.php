<?php

namespace Billplz\Base\Bill;

use Billplz\Request;

abstract class Transaction extends Request
{
    /**
     * Show an existing bill transactions.
     *
     * @param  string  $id
     * @param  array   $optional
     *
     * @return \Laravie\Codex\Response
     */
    public function show($id, array $optional = [])
    {
        return $this->send('GET', "bills/{$id}/transactions", [], $optional);
    }
}
