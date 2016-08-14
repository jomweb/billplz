<?php

namespace Billplz\Three;

use Money\Money;
use Billplz\Client;

class Collection extends Version
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
     * @param  array  $optional
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create($title, array $optional = [])
    {
        $body = array_merge(compact('title'), $optional);

        return $this->client->send('POST', $this->endpoint('collections'), [], $body);
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

        return $this->client->send('POST', $this->endpoint('open_collections'), [], $body);
    }
}
