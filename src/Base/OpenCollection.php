<?php

namespace Billplz\Base;

use Billplz\Request;

class OpenCollection extends Request
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
        $title,
        $description,
        $amount,
        array $optional = []
    ) {
        $body = array_merge(compact('title', 'description', 'amount'), $optional);

        return $this->send('POST', 'open_collections', [], $body);
    }

    /**
     * Get open collection.
     *
     * @param  string  $collectionId
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get($collectionId)
    {
        return $this->send('GET', "open_collections/{$collectionId}", [], []);
    }

    /**
     * Get open collection index.
     *
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function all(array $optional = [])
    {
        return $this->send('GET', 'open_collections', [], $optional);
    }
}
