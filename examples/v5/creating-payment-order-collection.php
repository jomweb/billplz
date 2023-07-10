<?php

require 'vendor/autoload.php';

$api = '';
$signatureKey = '';
$paymentOrderCollectionId = '';

$billplz = Billplz\Client::make($api, $signatureKey)->useSandbox();

$response = $billplz->paymentOrderCollection()->create(
    'test',
    [
        'callback_url' => 'http://example.com/webhook',
    ],
);

var_dump($response->getStatusCode(), $response->toArray());
