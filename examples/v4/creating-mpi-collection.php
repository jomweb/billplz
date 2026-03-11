<?php

use Billplz\Client;

require 'vendor/autoload.php';

$api = 'xxx';

$billplz = Client::make($api)->useSandbox();

$response = $billplz->collection('v4')->massPayment()->create('testing mpi collection');

var_dump($response->getStatusCode(), $response->toArray());
