<?php

namespace Billplz\Base;

use Billplz\Request;
use Laravie\Codex\Support\MultipartRequest;

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
    public function create($title, array $optional = [])
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
    public function get($id)
    {
        return $this->send('GET', "collections/{$id}", [], []);
    }

    /**
     * Get collection index.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function index(array $optional = [])
    {
        return $this->send('GET', 'collections', [], $optional);
    }

    /**
     * Get collection index.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function all(array $optional = [])
    {
        return $this->index($optional);
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
    public function createOpen($title, $description, $amount, array $optional = [])
    {
        return $this->client->uses('OpenCollection', $this->getVersion())
                    ->create($title, $description, $amount, $optional);
    }

    /**
     * Get open collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function getOpen($id)
    {
        return $this->client->uses('OpenCollection', $this->getVersion())
                    ->show($id);
    }

    /**
     * Get open collection index.
     *
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function indexOpen(array $optional = [])
    {
        return $this->client->uses('OpenCollection', $this->getVersion())
                    ->all($optional);
    }

    /**
     * Activate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function activate($id)
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
    public function deactivate($id)
    {
        return $this->send('POST', "collections/{$id}/deactivate", [], []);
    }
}
