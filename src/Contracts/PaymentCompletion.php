<?php

namespace Billplz\Contracts;

interface PaymentCompletion
{
    /**
     * Parse redirect data for a bill.
     */
    public function redirect(array $data = []): ?array;

    /**
     * Parse webhook data for a bill.
     */
    public function webhook(array $data = []): ?array;
}
