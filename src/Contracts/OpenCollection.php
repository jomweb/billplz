<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface OpenCollection extends Request
{
    /**
     * Create a new open collection.
     *
     * @param  string  $title
     * @param  string  $description
     * @param  \Money\Money|\Duit\MYR|int  $amount
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function create(
        string $title,
        string $description,
        $amount,
        array $optional = []
    ): Response;

    /**
     * Get open collection.
     *
     * @param  string  $collectionId
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $collectionId): Response;

    /**
     * Get open collection index.
     *
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function all(array $optional = []): Response;
}
