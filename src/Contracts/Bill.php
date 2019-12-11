<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Bill extends Request
{
    /**
     * Create a new bill.
     *
     * @param  \Money\Money|\Duit\MYR|int  $amount
     * @param  \Billplz\Contracts\PaymentCompletion|string $paymentCompletion
     *
     * @throws \InvalidArgumentException
     */
    public function create(
        string $collectionId,
        ?string $email,
        ?string $mobile,
        string $name,
        $amount,
        $paymentCompletion,
        string $description,
        array $optional = []
    ): Response;

    /**
     * Show an existing bill.
     */
    public function get(string $id): Response;

    /**
     * Show an existing bill transactions.
     */
    public function transaction(string $id, array $optional = []): Response;

    /**
     * Destroy an existing bill.
     */
    public function destroy(string $id): Response;
}
