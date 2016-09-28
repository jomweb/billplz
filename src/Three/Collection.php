<?php

namespace Billplz\Three;

use Laravie\Codex\Support\MultipartRequest;

class Collection extends Request
{
    use MultipartRequest;

    /**
     * Create a new collection.
     *
     * @param  string  $title
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Response
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
     * Create a new open collection.
     *
     * @param  string  $title
     * @param  string  $description
     * @param  \Money\Money|int  $amount
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Response
     */
    public function createOpen($title, $description, $amount, array $optional = [])
    {
        $amount = $money->getAmount();
        $body = array_merge(compact('title', 'description', 'amount'), $optional);

        return $this->send('POST', 'open_collections', [], $body);
    }

    /**
     * Activate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Response
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
     * @return \Laravie\Codex\Response
     */
    public function deactivate($id)
    {
        return $this->send('POST', "collections/{$id}/deactivate", [], []);
    }
}
