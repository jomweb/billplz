<?php

require 'vendor/autoload.php';

$api = '';
$signatureKey = '';

$billplz = Billplz\Client::make($api, $signatureKey)->useSandbox();

$response = $billplz->paymentOrder()->limit();

var_dump($response->getStatusCode(), $response->toArray());
