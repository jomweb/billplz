<?php

require 'vendor/autoload.php';

$api = 'xxx';

$billplz = Billplz\Client::make($api)->useSandbox();

$response = $billplz->collection('v4')->massPayment()->get('do4wg1tj');

var_dump($response->toArray());
