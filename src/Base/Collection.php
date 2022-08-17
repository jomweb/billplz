<?php

namespace Billplz\Base;

use Billplz\Contracts\Collection as Contract;
use Billplz\Request;
use Laravie\Codex\Concerns\Request\Multipart;
use Laravie\Codex\Contracts\Response;

abstract class Collection extends Request implements Contract
{
    use Multipart;

    /**
     * Create a new collection.
     *
     * @param  array<string, mixed>  $optional
     */
    public function create(string $title, array $optional = []): Response
    {
        $body = array_merge(compact('title'), $optional);

        return $this->stream('POST', 'collections', [], $body);
    }

    /**
     * Get collection.
     */
    public function get(string $id): Response
    {
        return $this->send('GET', "collections/{$id}", [], []);
    }

    /**
     * Get collection index.
     *
     * @param  array<string, mixed>  $optional
     */
    public function all(array $optional = []): Response
    {
        return $this->send('GET', 'collections', [], $optional);
    }

    /**
     * Activate a collection.
     */
    public function activate(string $id): Response
    {
        return $this->send('POST', "collections/{$id}/activate", [], []);
    }

    /**
     * Deactivate a collection.
     */
    public function deactivate(string $id): Response
    {
        return $this->send('POST', "collections/{$id}/deactivate", [], []);
    }
}
