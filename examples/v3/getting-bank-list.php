<?php
/**
 * Created by PhpStorm.
 * User: khairul
 * Date: 30/10/2017
 * Time: 12:43 PM
 */

require "vendor/autoload.php";

$api = 'xxx';

$billplz = Billplz\Client::make($api)->useSandbox();

$bank = $billplz->bank();
$list = $bank->supportedForFpx();

var_dump($list->toArray());