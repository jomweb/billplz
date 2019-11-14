<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Response;

interface Bill extends PaymentCompletion
{
    /**
     * Create a new bill.
     *
     * @param  \Money\Money|\Duit\MYR|int  $amount
     * @param  array|string  $callbackUrl
     *
     * @throws \InvalidArgumentException
     */
    public function create(
        string $collectionId,
        ?string $email,
        ?string $mobile,
        string $name,
        $amount,
        $callbackUrl,
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
