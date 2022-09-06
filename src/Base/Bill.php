<?php

namespace Billplz\Base;

use Billplz\Contracts\Bill as Contract;
use Billplz\Contracts\PaymentCompletion as PaymentCompletionContract;
use Billplz\PaymentCompletion as PaymentCompletionUrl;
use Billplz\Request;
use InvalidArgumentException;
use Laravie\Codex\Concerns\Request\Multipart;
use Laravie\Codex\Contracts\Response;

abstract class Bill extends Request implements Contract
{
    use Multipart,
        PaymentCompletion;

    /**
     * Create a new bill.
     *
     * @param  \Money\Money|\Duit\MYR|int  $amount
     * @param  \Billplz\Contracts\PaymentCompletion|string  $paymentCompletion
     * @param  array<string, mixed>  $optional
     *
     * @throws \InvalidArgumentException
     */
    public function create(
        string $collectionId,
        ?string $email,
        ?string $mobile,
        string $name,
        $amount,
        $paymentCompletion,
        string $description,
        array $optional = []
    ): Response {
        if (empty($email) && empty($mobile)) {
            throw new InvalidArgumentException('Either $email or $mobile should be present');
        }

        $body = array_merge(
            compact('email', 'mobile', 'name', 'amount', 'description'), $optional
        );

        $body['collection_id'] = $collectionId;

        $paymentCompletion = $paymentCompletion instanceof PaymentCompletionContract
            ? $paymentCompletion
            : new PaymentCompletionUrl($paymentCompletion, $optional['redirect_url'] ?? null);

        $body = array_merge($body, $paymentCompletion->toArray());

        return $this->stream('POST', 'bills', [], $body);
    }

    /**
     * Show an existing bill.
     */
    public function get(string $id): Response
    {
        return $this->send('GET', "bills/{$id}");
    }

    /**
     * Show an existing bill transactions.
     *
     * @param  array<string, mixed>  $optional
     */
    public function transaction(string $id, array $optional = []): Response
    {
        /** @var \Billplz\Contracts\Bill\Transaction $transaction */
        $transaction = $this->client->uses(
            'Bill.Transaction', $this->getVersion()
        );

        return $transaction->get($id, $optional);
    }

    /**
     * Destroy an existing bill.
     */
    public function destroy(string $id): Response
    {
        return $this->send('DELETE', "bills/{$id}");
    }
}
