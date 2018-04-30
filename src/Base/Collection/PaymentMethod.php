<?php

namespace Billplz\Base\Collection;

use Billplz\Request;
use Laravie\Codex\Contracts\Response;

class PaymentMethod extends Request
{
    /**
     * Get payment method index.
     *
     * @param  string  $collectionId
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $collectionId): Response
    {
        return $this->send('GET', "collections/{$collectionId}/payment_methods", [], []);
    }

    /**
     * Update payment methods.
     *
     * @param  string  $id
     * @param  array   $codes
     *
     * @return \Laravie\Codex\Contracts\Response
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
