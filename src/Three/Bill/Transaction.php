<?php

namespace Billplz\Three\Bill;

use Billplz\Three\Request;

class Transaction extends Request
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
