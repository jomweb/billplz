<?php
require 'vendor/autoload.php';

$api = '';
$signatureKey = '';
$paymentOrderCollectionId = '';

$billplz = Billplz\Client::make($api, $signatureKey)->useSandbox();

$response = $billplz->paymentOrderCollection()->get(
    $paymentOrderCollectionId
);

var_dump($response->getStatusCode(), $response->toArray());