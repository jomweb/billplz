<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface PaymentOrder extends Request
{
    /**
     * Create a Payment Order
     *
     * @param  array<string, mixed>  $optional
     */
    public function create(
        string $paymentOrderCollectionId,
        string $bankCode,
        string $bankAccountNumber,
        string $name,
        string $description,
        int $total,
        array $optional = [],
        ?int $epoch = null
    ): Response;

    /**
     * Get a Payment Order
     */
    public function get(
        string $paymentOrderId,
        ?int $epoch = null
    ): Response;

    /**
     * Get a Payment Order Limit
     */
    public function limit(?int $epoch = null): Response;
}
