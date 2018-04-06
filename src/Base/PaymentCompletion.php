<?php

namespace Billplz\Base;

use Billplz\Signature;
use Billplz\Exceptions\FailedSignatureVerification;

trait PaymentCompletion
{
    /**
     * Parse webhook data for a bill.
     *
     * @param  array  $data
     *
     * @return array|null
     */
    public function webhook(array $data = [])
    {
        $validated = $this->validateAgainstSignature($data, $this->client->getSignatureKey(), [
            'amount', 'collection_id', 'due_at', 'email', 'id', 'mobile', 'name',
            'paid_amount', 'paid_at', 'paid', 'state', 'url',
        ]);

        if ((bool) $validated) {
            return $this->sanitizeTo($data);
        }
    }

    /**
     * Validate against x-signature.
     *
     * @param  array  $bill
     * @param  string|null  $signatureKey
     * @param  array  $parameters
     *
     * @throws \Billplz\Exceptions\FailedSignatureVerification
     *
     * @return bool
     */
    final protected function validateAgainstSignature(array $bill, ?string $signatureKey = null, array $parameters = []): bool
    {
        if (is_null($signatureKey)) {
            return true;
        }

        if (! isset($bill['x_signature'])) {
            return false;
        }

        $signature = new Signature($signatureKey, $parameters);

        if (! $signature->verify($bill, $bill['x_signature'])) {
            throw new FailedSignatureVerification();
        }

        return true;
    }
}
