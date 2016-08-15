<?php

require "vendor/autoload.php";

$api = 'xxx';

$billplz = Billplz\Client::make($api)->useSandbox();

$response = $billplz->collection()->create('My Second API Collection');

var_dump(json_decode($response->getBody(), true));
