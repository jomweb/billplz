<?php

namespace Billplz\Contracts\Collection;

use Laravie\Codex\Contracts\Response;

interface MassPayment
{
    /**
     * Create a new mass payment instruction (mpi) collection.
     *
     * @param  string  $title
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function create(string $title): Response;

    /**
     * Get mass payment instruction (mpi) collection.
     *
     * @param  string  $collectionId
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $collectionId): Response;
}
