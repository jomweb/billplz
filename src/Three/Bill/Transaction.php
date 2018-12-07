<?php

namespace Billplz\Three\Bill;

use Billplz\Base\Bill\Transaction as Request;
use Billplz\Contracts\Bill\Transaction as Contract;

class Transaction extends Request implements Contract
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v3';
}
