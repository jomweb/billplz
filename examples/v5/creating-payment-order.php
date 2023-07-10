<?php

require 'vendor/autoload.php';

$api = '';
$signatureKey = '';
$paymentOrderCollectionId = '';

$billplz = Billplz\Client::make($api, $signatureKey)->useSandbox();

$response = $billplz->paymentOrder()->create(
    $paymentOrderCollectionId,
    'MBBEMYKL',
    '123456789012',
    '123456789012',
    'Ameer Shah',
    'Payment Order',
    1000,
);

var_dump($response->getStatusCode(), $response->toArray());
