<?php

namespace Billplz\Base\Collection;

use Billplz\Request;

class PaymentMethod extends Request
{
    /**
     * Get payment method index.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get($id)
    {
        return $this->send('GET', "collections/{$id}/payment_methods", [], []);
    }

    /**
     * Update payment methods.
     *
     * @param  string  $id
     * @param  array   $paymentMethods
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function update($id, array $paymentMethods)
    {
        return $this->send('PUT', "collections/{$id}/payment_methods", [], $paymentMethods);
    }
}
