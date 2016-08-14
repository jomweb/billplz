<?php

namespace Billplz;

use Money\Money;

class Collection
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create($title, array $data = [])
    {
        return $this->client->send('POST', 'collections', [], array_merge(compact('title'), $data));
    }

    public function createOpen($title, $description, Money $money, array $data = [])
    {
        $amount = $money->getAmount();

        return $this->client->send('POST', 'open_collections', [], array_merge(compact('title', 'description', 'amount'), $data));
    }
}
