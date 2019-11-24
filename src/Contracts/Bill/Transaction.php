<?php

namespace Billplz\Contracts\Bill;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Transaction extends Request
{
    /**
     * Show an existing bill transactions.
     */
    public function get(string $id, array $optional = []): Response;
}
