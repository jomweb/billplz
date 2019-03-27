<?php

namespace Billplz\Base;

use Billplz\Signature;
use InvalidArgumentException;
use Billplz\Exceptions\FailedSignatureVerification;

trait PaymentCompletion
{
    /**
     * Parse redirect data for a bill.
     *
     * @param  array  $data
     *
     * @return array|null
     */
    public function redirect(array $data = []): ?array
    {
        if (! isset($data['billplz']) || ! \is_array($data['billplz'])) {
            throw new InvalidArgumentException('Expected $billplz to be an array!');
        }

        $bill = [
            'billplzid' => $data['billplz']['id'],
            'billplzpaid' => $data['billplz']['paid'],
            'billplzpaid_at' => $data['billplz']['paid_at'],
            'x_signature' => $data['billplz']['x_signature'] ?? null,
        ];

        $validated = $this->validateAgainstSignature($bill, $this->client->getSignatureKey(), [
            'billplzid', 'billplzpaid_at', 'billplzpaid',
        ]);

        if ((bool) $validated) {
            return $this->filterResponse($data['billplz']);
        }

        return null;
    }

    /**
     * Parse webhook data for a bill.
     *
     * @param  array  $data
     *
     * @return array|null
     */
    public function webhook(array $data = []): ?array
    {
        $validated = $this->validateAgainstSignature($data, $this->client->getSignatureKey(), [
            'amount', 'collection_id', 'due_at', 'email', 'id', 'mobile', 'name',
            'paid_amount', 'paid_at', 'paid', 'state', 'url',
        ]);

        if ((bool) $validated) {
            return $this->filterResponse($data);
        }

        return null;
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
        if (\is_null($signatureKey)) {
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
