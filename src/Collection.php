<?php

namespace Billplz;

class Collection
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create($title, array $data = [])
    {
        return $this->client->send('POST', 'collections', [], array_merge(compact('title'), $data)));
    }

    public function createOpen($title, $description, $amount, array $data = [])
    {
        //
    }
}
