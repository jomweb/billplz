<?php

namespace Billplz\Base;

use Billplz\Request;
use Laravie\Codex\Support\MultipartRequest;
use Laravie\Codex\Contracts\Response as ResponseContract;

abstract class Collection extends Request
{
    use MultipartRequest;

    /**
     * Create a new collection.
     *
     * @param  string  $title
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function create(string $title, array $optional = []): ResponseContract
    {
        $files = [];
        $body = array_merge(compact('title'), $optional);

        if (isset($body['logo'])) {
            $files['logo'] = ltrim($body['logo'], '@');
            unset($body['logo']);
        }

        list($headers, $stream) = $this->prepareMultipartRequestPayloads([], $body, $files);

        return $this->send('POST', 'collections', $headers, $stream);
    }

    /**
     * Get collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $id): ResponseContract
    {
        return $this->send('GET', "collections/{$id}", [], []);
    }

    /**
     * Get collection index.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function index(array $optional = []): ResponseContract
    {
        return $this->send('GET', 'collections', [], $optional);
    }

    /**
     * Create a new open collection.
     *
     * @param  string  $title
     * @param  string  $description
     * @param  \Money\Money|int  $amount
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function createOpen(
        string $title,
        string $description,
        $amount,
        array $optional = []
    ): ResponseContract {
        $body = array_merge(compact('title', 'description', 'amount'), $optional);

        return $this->send('POST', 'open_collections', [], $body);
    }

    /**
     * Get open collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function getOpen(string $id): ResponseContract
    {
        return $this->send('GET', "open_collections/{$id}", [], []);
    }

    /**
     * Get open collection index.
     *
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function indexOpen(array $optional = []): ResponseContract
    {
        return $this->send('GET', 'open_collections', [], $optional);
    }

    /**
     * Activate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function activate(string $id): ResponseContract
    {
        return $this->send('POST', "collections/{$id}/activate", [], []);
    }

    /**
     * Deactivate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function deactivate(string $id): ResponseContract
    {
        return $this->send('POST', "collections/{$id}/deactivate", [], []);
    }
}
