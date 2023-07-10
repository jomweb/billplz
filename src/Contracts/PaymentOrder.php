<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface PaymentOrder extends Request
{
    /**
     * Create a Payment Order
     *
     * @param  int  $total
     * @param  array<string, mixed>  $optional
     */
    public function create(
        string $paymentOrderCollectionId,
        string $bankCode,
        string $bankAccountNumber,
        string $identityNumber,
        string $name,
        string $description,
        $total,
        array $optional = []
    ): Response;

    /**
     * Get a Payment Order
     */
    public function get(
        string $paymentOrderId,
    ): Response;

    /**
     * Get a Payment Order Limit
     */
    public function limit(): Response;
}
