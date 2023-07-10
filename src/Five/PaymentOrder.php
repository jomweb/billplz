<?php

namespace Billplz\Five;

use Billplz\Checksum;
use Billplz\Contracts\PaymentOrder as Contract;
use Billplz\Request;
use Laravie\Codex\Contracts\Response;

class PaymentOrder extends Request implements Contract
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v5';

    /**
     * Create a Payment Order
     *
     * @param  int  $total
     */
    public function create(
        string $paymentOrderCollectionId,
        string $bankCode,
        string $bankAccountNumber,
        string $identityNumber,
        string $name,
        string $description,
        $total,
        array $optional = []
    ): Response {
        $epoch = time();

        $body['payment_order_collection_id'] = $paymentOrderCollectionId;
        $body['bank_code'] = $bankCode;
        $body['bank_account_number'] = $bankAccountNumber;
        $body['identity_number'] = $identityNumber;
        $body['name'] = $name;
        $body['description'] = $description;
        $body['total'] = $total;
        $body['epoch'] = $epoch;
        $body['checksum'] = Checksum::create($this->client->getSignatureKey(), [
            $paymentOrderCollectionId,
            $bankAccountNumber,
            $total,
            $epoch,
        ]);

        $body = array_merge($body, $optional);

        return $this->send('POST', 'payment_orders', [], $body);
    }

    /**
     * Get a Payment Order
     */
    public function get(
        string $paymentOrderId,
    ): Response {
        $epoch = time();

        $body['payment_order_id'] = $paymentOrderId;
        $body['epoch'] = $epoch;
        $body['checksum'] = Checksum::create($this->client->getSignatureKey(), [
            $paymentOrderId,
            $epoch,
        ]);

        return $this->send('GET', "payment_orders/{$paymentOrderId}", [], $body);
    }

    /**
     * Get a Payment Order Limit
     */
    public function limit(): Response
    {
        $epoch = time();

        $body['epoch'] = $epoch;
        $body['checksum'] = Checksum::create($this->client->getSignatureKey(), [
            $epoch,
        ]);

        return $this->send('GET', 'payment_order_limit', [], $body);
    }
}
