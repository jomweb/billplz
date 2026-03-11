<?php

use Billplz\Client;

require 'vendor/autoload.php';

$api = 'xxx';
$bill = 'v3bfqg';

$billplz = Client::make($api)->useSandbox();

$response = $billplz->bill()->transaction($bill);

var_dump($response->getStatusCode(), $response->toArray());
