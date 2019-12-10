<?php

namespace Billplz\Contracts;

interface PaymentCompletion
{
    /**
     * Get Webhook URL.
     */
    public function webhookUrl(): string;

    /**
     * Get Redirect URL.
     */
    public function redirectUrl(): ?string;

    /**
     * Convert to array.
     */
    public function toArray(): array;
}
