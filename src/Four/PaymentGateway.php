<?php

namespace Billplz\Four;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;

class PaymentGateway extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v4';

    /**
     * Get payment gateways index.
     *
     * @return \Billplz\Response
     */
    public function all(): Response
    {
        return $this->send('GET', 'payment_gateways', [], []);
    }
}
