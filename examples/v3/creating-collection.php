<?php

require 'vendor/autoload.php';

$api = 'xxx';

$billplz = Billplz\Client::make($api)->useSandbox();

$response = $billplz->collection()->create('My First API Collection');

var_dump($response->getStatusCode(), $response->toArray());
