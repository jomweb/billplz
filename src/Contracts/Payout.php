<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Payout extends Request
{
    /**
     * Create a new mass payment instruction (mpi).
     *
     * @param  int  $total
     */
    public function create(
        string $collectionId,
        string $bankCode,
        string $bankAccountNumber,
        string $identityNumber,
        string $name,
        string $description,
        $total,
        array $optional = []
    ): Response;

    /**
     * Get mass payment instruction (mpi).
     */
    public function get(string $instructionId): Response;
}
