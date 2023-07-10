<?php

require 'vendor/autoload.php';

$api = '';
$signatureKey = '';
$paymentOrderId = '';

$billplz = Billplz\Client::make($api, $signatureKey)->useSandbox();

$response = $billplz->paymentOrder()->get(
    $paymentOrderId
);

var_dump($response->getStatusCode(), $response->toArray());
