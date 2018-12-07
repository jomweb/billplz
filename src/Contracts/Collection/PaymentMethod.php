<?php

namespace Billplz\Contracts\Collection;

use Laravie\Codex\Contracts\Response;

interface PaymentMethod
{
    /**
     * Get payment method index.
     *
     * @param  string  $collectionId
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $collectionId): Response;

    /**
     * Update payment methods.
     *
     * @param  string  $id
     * @param  array   $codes
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function update(string $collectionId, array $codes = []): Response;
}
