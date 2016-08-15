<?php

require "vendor/autoload.php";

$api = 'b4140499-f2d1-45eb-af2c-a43f67a301cb';

$billplz = Billplz\Client::make($api)->useSandbox();


$response = $billplz->collection()->create('My First API Collection');

var_dump(json_decode($response->getBody(), true));
