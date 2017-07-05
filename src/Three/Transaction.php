<?php

namespace Billplz\Three;

class Transaction extends Request
{
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
