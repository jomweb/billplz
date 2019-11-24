<?php

namespace Billplz\Contracts\Collection;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Payout extends Request
{
    /**
     * Create a new mass payment instruction (mpi) collection.
     */
    public function create(string $title): Response;

    /**
     * Get mass payment instruction (mpi) collection.
     */
    public function get(string $collectionId): Response;
}
