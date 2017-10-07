<?php

require "vendor/autoload.php";

$api = 'xxx';

$billplz = Billplz\Client::make($api)->useSandbox();

$response = $billplz->mpiCollection('v4')->create('testing mpi collection');

var_dump($response->toArray());
