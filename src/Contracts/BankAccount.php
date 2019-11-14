<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Response;

interface BankAccount
{
    /**
     * Get A Bank Account.
     */
    public function get(string $accountNumber): Response;

    /**
     * Create A Bank Account.
     */
    public function create(
        string $name,
        string $identification,
        string $accountNumber,
        string $code,
        bool $organization
    ): Response;
}
