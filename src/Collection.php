<?php

namespace Billplz;

use Money\Money;

class Collection
{
    /**
     * The Billplz client.
     *
     * @var \Billplz\Client
     */
    protected $client;

    /**
     * Construct a new Collection.
     *
     * @param \Billplz\Client  $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new collection.
     *
     * @param  string  $title
     * @param  array  $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create($title, array $data = [])
    {
        return $this->client->send('POST', 'collections', [], array_merge(compact('title'), $data));
    }

    /**
     * Create a new open collection.
     *
     * @param  string  $title
     * @param  string  $description
     * @param  \Money\Money  $money
     * @param  array  $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createOpen($title, $description, Money $money, array $data = [])
    {
        $amount = $money->getAmount();

        $body = array_merge(compact('title', 'description', 'amount'), $data);

        return $this->client->send('POST', 'open_collections', [], $body);
    }
}
