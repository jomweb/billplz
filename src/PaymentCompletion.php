<?php

namespace Billplz;

class PaymentCompletion implements Contracts\PaymentCompletion
{
    /**
     * Webhook URL.
     *
     * @var string
     */
    protected $webhookUrl;

    /**
     * Redirect URL.
     *
     * @var string|null
     */
    protected $redirectUrl;

    /**
     * Construct Payment Completion.
     */
    public function __construct(string $callbackUrl, ?string $redirectUrl = null)
    {
        $this->webhookUrl = $callbackUrl;
        $this->redirectUrl = $redirectUrl;
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
     */
    public function toArray(): array
    {
        return [
            'callback_url' => $this->webhookUrl,
            'redirect_url' => $this->redirectUrl,
        ];
    }
}
