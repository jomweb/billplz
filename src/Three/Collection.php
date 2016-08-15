<?php

namespace Billplz\Three;

use Money\Money;

class Collection extends Request
{
    /**
     * Create a new collection.
     *
     * @param  string  $title
     * @param  array  $optional
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create($title, array $optional = [])
    {
        $body = array_merge(compact('title'), $optional);

        list($uri, $headers) = $this->endpoint('collections');

        return $this->client->send('POST', $uri, $headers, $body);
    }

    /**
     * Create a new open collection.
     *
     * @param  string  $title
     * @param  string  $description
     * @param  \Money\Money  $money
     * @param  array  $optional
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createOpen($title, $description, Money $money, array $optional = [])
    {
        $amount = $money->getAmount();

        $body = array_merge(compact('title', 'description', 'amount'), $optional);

        list($uri, $headers) = $this->endpoint('open_collections');

        return $this->client->send('POST', $uri, $headers, $body);
    }
}
