<?php

namespace Billplz\Three\Bill;

use Billplz\Base\Bill\Transaction as Request;

class Transaction extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v3';
}
