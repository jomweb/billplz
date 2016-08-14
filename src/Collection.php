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

    }

    public function show($id)
    {

    }

    public function destroy($id)
    {

    }
}
