<?php

namespace Billplz;

class PaymentCompletion implements Contracts\PaymentCompletion
{
    /**
     * Construct Payment Completion.
     */
    public function __construct(
        protected string $webhookUrl,
        protected ?string $redirectUrl = null
    ) {
        //
    }

    /**
     * Get Webhook URL.
     */
    public function webhookUrl(): string
    {
        return $this->webhookUrl;
    }

    /**
     * Get Redirect URL.
     */
    public function redirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    /**
     * Convert to array.
     *
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'callback_url' => $this->webhookUrl,
            'redirect_url' => $this->redirectUrl,
        ];
    }
}
