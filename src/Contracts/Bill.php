<?php

namespace Billplz\Contracts;

use Laravie\Codex\Contracts\Request;
use Laravie\Codex\Contracts\Response;

interface Bill extends Request
{
    /**
     * Create a new bill.
     *
     * @param  string  $collectionId
     * @param  string|null  $email
     * @param  string|null  $mobile
     * @param  string  $name
     * @param  \Money\Money|\Duit\MYR|int  $amount
     * @param  array|string  $callbackUrl
     * @param  string  $description
     * @param  array  $optional
     *
     * @throws \InvalidArgumentException
     *
     * @return \Laravie\Codex\Contracts\Response
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
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function get(string $id): Response;

    /**
     * Show an existing bill transactions.
     *
     * @param  string  $id
     * @param  array   $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function transaction(string $id, array $optional = []): Response;

    /**
     * Destroy an existing bill.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function destroy(string $id): Response;
}
