<?php

namespace Billplz\Five;

use Billplz\Checksum;
use Billplz\Contracts\PaymentOrderCollection as Contract;
use Billplz\Request;
use Laravie\Codex\Contracts\Response;

class PaymentOrderCollection extends Request implements Contract
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v5';

    /**
     * Create a Payment Order Collection
     */
    public function create(
        string $title,
        array $optional = []
    ): Response {
        $epoch = time();

        $body['title'] = $title;
        $body['epoch'] = $epoch;

        $checksum_data = [
            $title,
            $epoch,
        ];

        if (array_key_exists('callback_url', $optional)) {
            $checksum_data = [
                $title,
                $optional['callback_url'],
                $epoch,
            ];
        }

        $body['checksum'] = Checksum::create($this->client->getSignatureKey(), $checksum_data);
        $body = array_merge($body, $optional);

        return $this->send('POST', 'payment_order_collections', [], $body);
    }

    /**
     * Get a Payment Order Collection
     */
    public function get(
        string $paymentOrderCollectionId,
    ): Response {
        $epoch = time();

        $body['payment_order_collection_id'] = $paymentOrderCollectionId;
        $body['epoch'] = $epoch;
        $body['checksum'] = Checksum::create($this->client->getSignatureKey(), [
            $paymentOrderCollectionId,
            $epoch,
        ]);

        return $this->send('GET', "payment_order_collections/{$paymentOrderCollectionId}", [], $body);
    }
}
