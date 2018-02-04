<?php

require 'vendor/autoload.php';

$api = 'xxx';
$bill = 'v3bfqg';

$billplz = Billplz\Client::make($api)->useSandbox();

$response = $billplz->bill()->transaction($bill);

var_dump($response->toArray());
