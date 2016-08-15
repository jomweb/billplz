<?php

namespace Billplz\Two;

use Billplz\Client;
use Billplz\Version;

abstract class Request extends Version
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v2';

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
}
