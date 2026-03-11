<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface PaymentOrderCollection extends Request
{
    /**
     * Create a Payment Order Collection
     *
     * @param  array<string, mixed>  $optional
     */
    public function create(
        string $title,
        array $optional = [],
        ?int $epoch = null
    ): Response;

    /**
     * Get a Payment Order Collection
     */
    public function get(
        string $paymentOrderCollectionId,
        ?int $epoch = null
    ): Response;
}
