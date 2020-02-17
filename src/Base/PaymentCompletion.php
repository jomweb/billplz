<?php

namespace Billplz\Base;

use Billplz\Signature;
use InvalidArgumentException;
use Billplz\Exceptions\FailedSignatureVerification;

trait PaymentCompletion
{
    /**
     * Parse redirect data for a bill.
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
            'x_signature' => $data['billplz']['x_signature'] ?? null,
        ];

        $validated = $this->validateAgainstSignature(
            $bill, $this->client->getSignatureKey(), Signature::REDIRECT_PARAMETERS
        );

        $data['billplz']['paid'] = $data['billplz']['paid'] === 'true' ? true : false;

        return $validated === true ? $this->filterResponse($data['billplz']) : null;
    }

    /**
     * Parse webhook data for a bill.
     */
    public function webhook(array $data = []): ?array
    {
        $validated = $this->validateAgainstSignature(
            $data, $this->client->getSignatureKey(), Signature::WEBHOOK_PARAMETERS
        );

        $data['paid'] = $data['paid'] === 'true' ? true : false;

        return $validated === true ? $this->filterResponse($data) : null;
    }

    /**
     * Validate against x-signature.
     *
     * @throws \Billplz\Exceptions\FailedSignatureVerification
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
