<?php

namespace Billplz\Base\Bill;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;

abstract class Transaction extends Request
{
    /**
     * Show an existing bill transactions.
     *
     * @param  string  $id
     * @param  array   $optional
     *
     * @return \Billplz\Response
     *
     * @deprecated v2.0.1
     * @see static::get()
     */
    public function show(string $id, array $optional = []): Response
    {
        return $this->get($id, $optional);
    }

    /**
     * Show an existing bill transactions.
     *
     * @param  string  $id
     * @param  array   $optional
     *
     * @return \Billplz\Response
     */
    public function get(string $id, array $optional = []): Response
    {
        return $this->send('GET', "bills/{$id}/transactions", [], $optional);
    }
}
