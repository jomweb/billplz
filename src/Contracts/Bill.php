<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;
use Money\Money;

interface Bill extends Request
{
    /**
     * Create a new bill.
     *
     * @param  array<string, mixed>  $optional
     *
     * @throws \InvalidArgumentException
     */
    public function create(
        string $collectionId,
        ?string $email,
        ?string $mobile,
        string $name,
        Money|int $amount,
        PaymentCompletion|string $paymentCompletion,
        string $description,
        array $optional = []
    ): Response;

    /**
     * Show an existing bill.
     */
    public function get(string $id): Response;

    /**
     * Show an existing bill transactions.
     *
     * @param  array<string, mixed>  $optional
     */
    public function transaction(string $id, array $optional = []): Response;

    /**
     * Destroy an existing bill.
     */
    public function destroy(string $id): Response;
}
