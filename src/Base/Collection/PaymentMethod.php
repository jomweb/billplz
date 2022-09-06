<?php

namespace Billplz\Base\Collection;

use Billplz\Contracts\Collection\PaymentMethod as Contract;
use Billplz\Request;
use Laravie\Codex\Contracts\Response;

class PaymentMethod extends Request implements Contract
{
    /**
     * Get payment method index.
     */
    public function get(string $collectionId): Response
    {
        return $this->send('GET', "collections/{$collectionId}/payment_methods", [], []);
    }

    /**
     * Update payment methods.
     *
     * @param  array<int, string>  $codes
     */
    public function update(string $collectionId, array $codes = []): Response
    {
        $payments = [];

        foreach ($codes as $code) {
            array_push($payments, compact('code'));
        }

        return $this->send('PUT', "collections/{$collectionId}/payment_methods", [], $payments);
    }
}
