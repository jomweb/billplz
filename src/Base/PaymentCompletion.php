<?php

namespace Billplz\Base;

use Billplz\Exceptions\FailedSignatureVerification;
use Billplz\Signature;
use InvalidArgumentException;

/**
 * @property \Billplz\Client $client
 */
trait PaymentCompletion
{
    /**
     * Parse redirect data for a bill.
     *
     * @param  array<string, array<string, mixed>>  $data
     */
    public function redirect(array $data = []): ?array
    {
        if (! isset($data['billplz']) || ! \is_array($data['billplz'])) {
            throw new InvalidArgumentException('Expected $billplz to be an array!');
        }

        if (! isset($data['billplz']['x_signature']) && isset($data['billplz']['id'])) {
            return \is_null($this->client->getSignatureKey()) ? $data['billplz'] : null;
        }

        $bill = [
            'billplzid' => $data['billplz']['id'],
            'billplzpaid' => $data['billplz']['paid'],
            'billplzpaid_at' => $data['billplz']['paid_at'],
            'billplztransaction_id' => $data['billplz']['transaction_id'] ?? null,
            'billplztransaction_status' => $data['billplz']['transaction_status'] ?? null,
            'x_signature' => $data['billplz']['x_signature'] ?? null,
        ];

        $validated = $this->validateAgainstSignature($bill, Signature::redirect($this->client->getSignatureKey()));

        $data['billplz']['paid'] = $data['billplz']['paid'] === 'true' ? true : false;

        return $validated === true ? $this->filterResponse($data['billplz']) : null;
    }

    /**
     * Parse webhook data for a bill.
     *
     * @param  array<string, mixed>  $data
     */
    public function webhook(array $data = []): ?array
    {
        $validated = $this->validateAgainstSignature($data, Signature::webhook($this->client->getSignatureKey()));

        $data['paid'] = $data['paid'] === 'true' ? true : false;

        return $validated === true ? $this->filterResponse($data) : null;
    }

    /**
     * Validate against x-signature.
     *
     * @throws \Billplz\Exceptions\FailedSignatureVerification
     */
    final protected function validateAgainstSignature(array $bill, Signature $signature): bool
    {
        if (! $signature->hasKey()) {
            return true;
        }

        if (! isset($bill['x_signature'])) {
            return false;
        }

        if (! $signature->verify($bill, $bill['x_signature'])) {
            throw new FailedSignatureVerification();
        }

        return true;
    }
}
