<?php

namespace Billplz\Four;

use Billplz\Request;

class PaymentMethod extends Request
{
	/**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v4';

    /**
     * Get payment method index.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function show($id)
    {
    	return $this->send('GET', "collections/{$id}/payment_methods", [], []);
    }
}