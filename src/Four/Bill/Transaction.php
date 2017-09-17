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
     *
     * @return \Laravie\Codex\Response
     */
    public function show($id)
    {
        return $this->send('GET', "bills/{$id}/transactions");
    }
}
