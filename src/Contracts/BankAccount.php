<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Response;

interface BankAccount
{
    /**
     * Get A Bank Account.
     *
     * @param  string  $accountNumber
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $accountNumber): Response;

    /**
     * Create A Bank Account.
     *
     * @param  string  $name
     * @param  string  $identification
     * @param  string  $accountNumber
     * @param  string  $code
     * @param  bool $organization
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function create(
        string $name,
        string $identification,
        string $accountNumber,
        string $code,
        bool $organization
    ): Response;
}
