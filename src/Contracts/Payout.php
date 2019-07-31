<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Response;

interface Payout
{
    /**
     * Create a new mass payment instruction (mpi).
     *
     * @param  string  $collectionId
     * @param  string  $bankCode
     * @param  string  $bankAccountNumber
     * @param  string  $identityNumber
     * @param  string  $name
     * @param  string  $description
     * @param  int  $total
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
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
     *
     * @param  string  $instructionId
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $instructionId): Response;
}
