<?php

namespace Billplz\Base;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;
use Laravie\Codex\Concerns\Request\Multipart;

abstract class Collection extends Request
{
    use Multipart;

    /**
     * Create a new collection.
     *
     * @param  string  $title
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function create(string $title, array $optional = []): Response
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
    public function get(string $id): Response
    {
        return $this->send('GET', "collections/{$id}", [], []);
    }

    /**
     * Get collection index.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function all(array $optional = []): Response
    {
        return $this->send('GET', 'collections', [], $optional);
    }

    /**
     * Activate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function activate(string $id): Response
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
    public function deactivate(string $id): Response
    {
        return $this->send('POST', "collections/{$id}/deactivate", [], []);
    }

    /**
     * Interacts with Response.
     *
     * @param  \Laravie\Codex\Contracts\Response $response
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function interactsWithResponse(Response $response): Response
    {
        return parent::interactsWithResponse($response)
                    ->validateWith(function ($code, $response) {
                        $response->abortIfRequestHasFailed();
                    });
    }
}
