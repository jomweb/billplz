<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface OpenCollection extends Request
{
    /**
     * Create a new open collection.
     *
     * @param  \Money\Money|\Duit\MYR|int  $amount
     * @param  array<string, mixed>  $optional
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
     *
     * @param  array<string, mixed>  $optional
     */
    public function all(array $optional = []): Response;
}
