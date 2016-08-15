<?php

namespace Billplz\Two;

use Billplz\Client;
use Billplz\Request as BaseRequest;

abstract class Request extends BaseRequest
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v2';

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
