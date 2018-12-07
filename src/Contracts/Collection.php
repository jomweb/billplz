<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Response;

interface Collection
{
    /**
     * Create a new collection.
     *
     * @param  string  $title
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function create(string $title, array $optional = []): Response;

    /**
     * Get collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $id): Response;

    /**
     * Get collection index.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function all(array $optional = []): Response;

    /**
     * Activate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function activate(string $id): Response;

    /**
     * Deactivate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function deactivate(string $id): Response;
}
