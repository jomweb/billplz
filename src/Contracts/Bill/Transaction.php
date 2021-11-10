<?php

namespace Billplz\Contracts\Bill;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Transaction extends Request
{
    /**
     * Show an existing bill transactions.
     *
     * @param  array<string, mixed>  $optional
     */
    public function get(string $id, array $optional = []): Response;
}
