<?php

require 'vendor/autoload.php';

$api = 'xxx';

$billplz = Billplz\Client::make($api)->useSandbox();

$bank = $billplz->bank();
$list = $bank->supportedForFpx();

var_dump($list->toArray());
