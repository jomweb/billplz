<?php

require "vendor/autoload.php";

$api = 'xxx';

$billplz = Billplz\Client::make($api)->useSandbox();

$response = $billplz->mpiCollection('v4')->get('do4wg1tj');

var_dump($response->toArray());
