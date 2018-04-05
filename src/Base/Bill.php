<?php

namespace Billplz\Base;

use Billplz\Request;
use Billplz\Signature;
use InvalidArgumentException;
use Billplz\Exceptions\FailedSignatureVerification;
use Laravie\Codex\Contracts\Response as ResponseContract;

abstract class Bill extends Request
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
    ): ResponseContract {
        if (empty($email) && empty($mobile)) {
            throw new InvalidArgumentException('Either $email or $mobile should be present');
        }

        $body = array_merge(
            compact('email', 'mobile', 'name', 'amount', 'description'),
            $optional
        );

        $body['collection_id'] = $collectionId;

        $body = $this->parseRedirectAndCallbackUrlFromRequest($body, $callbackUrl);

        return $this->send('POST', 'bills', [], $body);
    }

    /**
     * Show an existing bill.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function show(string $id): ResponseContract
    {
        return $this->send('GET', "bills/{$id}");
    }

    /**
     * Show an existing bill transactions.
     *
     * @param  string  $id
     * @param  array   $optional
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function transaction(string $id, array $optional = []): ResponseContract
    {
        return $this->client->uses('Bill.Transaction', $this->getVersion())
                    ->show($id, $optional);
    }

    /**
     * Destroy an existing bill.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function destroy(string $id): ResponseContract
    {
        return $this->send('DELETE', "bills/{$id}");
    }

    /**
     * Parse redirect data for a bill.
     *
     * @param  array  $data
     *
     * @return array|null
     */
    public function redirect(array $data = [])
    {
        if (! isset($data['billplz']) || ! is_array($data['billplz'])) {
            throw new InvalidArgumentException("Expected \$billplz to be an array!");
        }

        $bill = [
            'billplzid' => $data['billplz']['id'],
            'billplzpaid' => $data['billplz']['paid'],
            'billplzpaid_at' => $data['billplz']['paid_at'],
            'x_signature' => isset($data['billplz']['x_signature']) ? $data['billplz']['x_signature'] : null,
        ];

        $validated = $this->validateAgainstSignature($bill, $this->client->getSignatureKey(), [
            'billplzid', 'billplzpaid_at', 'billplzpaid',
        ]);

        if ((bool) $validated) {
            return $this->sanitizeTo($data['billplz']);
        }
    }

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
