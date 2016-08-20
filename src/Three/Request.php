<?php

namespace Billplz\Three;

use Billplz\Request as BaseRequest;

abstract class Request extends BaseRequest
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v3';
}
