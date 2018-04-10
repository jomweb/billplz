<?php

namespace Billplz\Three;

use Billplz\Request;

class PaymentMethod extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v3';

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

    /**
     * Update payment methods.
     *
     * @param  string  $id
     * @param  array   $payment_methods
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function update($id, $payment_methods = [])
    {
        return $this->send('PUT', "collections/{$id}/payment_methods", [], $payment_methods);
    }
}
