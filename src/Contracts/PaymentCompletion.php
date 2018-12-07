<?php

namespace Billplz\Contracts;

interface PaymentCompletion
{
    /**
     * Parse redirect data for a bill.
     *
     * @param  array  $data
     *
     * @return array|null
     */
    public function redirect(array $data = []): ?array;

    /**
     * Parse webhook data for a bill.
     *
     * @param  array  $data
     *
     * @return array|null
     */
    public function webhook(array $data = []): ?array;
}
