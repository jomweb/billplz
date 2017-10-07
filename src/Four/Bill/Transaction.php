<?php

namespace Billplz\Three\Bill;

use Billplz\Three\Request;

class Transaction extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v3';

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
