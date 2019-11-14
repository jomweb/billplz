<?php

namespace Billplz\Base\Collection;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;
use Billplz\Contracts\Collection\PaymentMethod as Contract;

class PaymentMethod extends Request implements Contract
{
    /**
     * Get payment method index.
     *
     * @return \Billplz\Response
     */
    public function get(string $collectionId): Response
    {
        return $this->send('GET', "collections/{$collectionId}/payment_methods", [], []);
    }

    /**
     * Update payment methods.
     *
     * @param  string  $id
     *
     * @return \Billplz\Response
     */
    public function update(string $collectionId, array $codes = []): Response
    {
        $payments = [];

        foreach ($codes as $code) {
            \array_push($payments, \compact('code'));
        }

        return $this->send('PUT', "collections/{$collectionId}/payment_methods", [], $payments);
    }
}
