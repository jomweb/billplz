<?php

namespace Billplz\Contracts\Bill;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Transaction extends Request
{
    /**
     * Show an existing bill transactions.
     *
     * @param  string  $id
     * @param  array   $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $id, array $optional = []): Response;
}
