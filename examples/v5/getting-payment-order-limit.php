<?php

use Billplz\Client;

require 'vendor/autoload.php';

$api = '';
$signatureKey = '';

$billplz = Client::make($api, $signatureKey)->useSandbox();

$response = $billplz->paymentOrder()->limit();

var_dump($response->getStatusCode(), $response->toArray());
