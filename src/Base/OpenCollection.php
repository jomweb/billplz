<?php

namespace Billplz\Base;

use Billplz\Contracts\OpenCollection as Contract;
use Billplz\Request;
use Laravie\Codex\Concerns\Request\Multipart;
use Laravie\Codex\Contracts\Response;

class OpenCollection extends Request implements Contract
{
    use Multipart;

    /**
     * Create a new open collection.
     *
     * @param  \Money\Money|\Duit\MYR|int  $amount
     * @param  array<string, mixed>  $optional
     */
    public function create(
        string $title,
        string $description,
        $amount,
        array $optional = []
    ): Response {
        $body = array_merge(compact('title', 'description', 'amount'), $optional);

        return $this->stream('POST', 'open_collections', [], $body);
    }

    /**
     * Get open collection.
     */
    public function get(string $collectionId): Response
    {
        return $this->send('GET', "open_collections/{$collectionId}", [], []);
    }

    /**
     * Get open collection index.
     *
     * @param  array<string, mixed>  $optional
     */
    public function all(array $optional = []): Response
    {
        return $this->send('GET', 'open_collections', [], $optional);
    }
}
