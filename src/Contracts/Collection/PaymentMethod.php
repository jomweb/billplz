<?php

namespace Billplz\Contracts\Collection;

use Laravie\Codex\Contracts\Response;

interface PaymentMethod
{
    /**
     * Get payment method index.
     */
    public function get(string $collectionId): Response;

    /**
     * Update payment methods.
     *
     * @param  string  $id
     */
    public function update(string $collectionId, array $codes = []): Response;
}
