<?php

require "vendor/autoload.php";

$api = 'xxx';
$bill = 'v3bfqg';

$billplz = Billplz\Client::make($api)->useSandbox();

$transaction = $billplz->transaction();

$response = $transaction->show($bill);

var_dump($response->toArray());
