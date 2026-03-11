<?php

use Billplz\Client;

require 'vendor/autoload.php';

$api = 'xxx';

$billplz = Client::make($api)->useSandbox();

$response = $billplz->collection()->create('My First API Collection');

var_dump($response->getStatusCode(), $response->toArray());
