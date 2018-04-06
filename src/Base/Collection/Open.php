<?php

namespace Billplz\Base\Collection;

use Billplz\Request;
use Laravie\Codex\Contracts\Response as ResponseContract;

class Open extends Request
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
    public function get(string $id): ResponseContract
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
    public function all(array $optional = []): ResponseContract
    {
        return $this->send('GET', 'open_collections', [], $optional);
    }
}
