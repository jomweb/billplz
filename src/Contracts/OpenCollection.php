<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Response;

interface OpenCollection
{
    /**
     * Create a new open collection.
     *
     * @param  \Money\Money|\Duit\MYR|int  $amount
     */
    public function create(
        string $title,
        string $description,
        $amount,
        array $optional = []
    ): Response;

    /**
     * Get open collection.
     */
    public function get(string $collectionId): Response;

    /**
     * Get open collection index.
     */
    public function all(array $optional = []): Response;
}
