<?php

namespace Billplz\Base;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;
use Billplz\Contracts\Collection as Contract;
use Laravie\Codex\Concerns\Request\Multipart;

abstract class Collection extends Request implements Contract
{
    use Multipart;

    /**
     * Create a new collection.
     *
     * @return \Billplz\Response
     */
    public function create(string $title, array $optional = []): Response
    {
        $body = \array_merge(\compact('title'), $optional);

        return $this->stream('POST', 'collections', [], $body);
    }

    /**
     * Get collection.
     *
     * @return \Billplz\Response
     */
    public function get(string $id): Response
    {
        return $this->send('GET', "collections/{$id}", [], []);
    }

    /**
     * Get collection index.
     *
     * @return \Billplz\Response
     */
    public function all(array $optional = []): Response
    {
        return $this->send('GET', 'collections', [], $optional);
    }

    /**
     * Activate a collection.
     *
     * @return \Billplz\Response
     */
    public function activate(string $id): Response
    {
        return $this->send('POST', "collections/{$id}/activate", [], []);
    }

    /**
     * Deactivate a collection.
     *
     * @return \Billplz\Response
     */
    public function deactivate(string $id): Response
    {
        return $this->send('POST', "collections/{$id}/deactivate", [], []);
    }
}
