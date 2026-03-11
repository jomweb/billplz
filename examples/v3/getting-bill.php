<?php

use Billplz\Client;

require 'vendor/autoload.php';

$api = 'xxx';
$bill = '6fj1aw';

$billplz = Client::make($api)->useSandbox();

$response = $billplz->bill()->get($bill);

var_dump($response->getStatusCode(), $response->toArray());
