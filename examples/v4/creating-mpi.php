<?php

require "vendor/autoload.php";

$api = 'xxx';

$billplz = Billplz\Client::make($api)->useSandbox();

$response = $billplz->mpi('v4')->create('do4wg1tj', 'BIMBMYKL', 'xxx', 'xxx', 'Hakim Razalan', 'testing', 200);

var_dump($response->toArray());
