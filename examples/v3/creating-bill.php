<?php

use Billplz\Client;
use Billplz\PaymentCompletion;

require 'vendor/autoload.php';

$api = 'xxx';
$collection = '2e97chf9';

$billplz = Client::make($api)->useSandbox();

$response = $billplz->bill()->create(
    $collection,
    'crynobone@gmail.com',
    null,
    'Mior Muhammad Zaki',
    300,
    new PaymentCompletion('https://localhost/webhook/billplz'),
    'My first bill'
);

var_dump($response->getStatusCode(), $response->toArray());
