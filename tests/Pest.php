<?php

use Billplz\Tests\TestCase;

require_once __DIR__.'/Base/BankAccountTests.php';
require_once __DIR__.'/Base/BillTests.php';
require_once __DIR__.'/Base/CollectionTests.php';
require_once __DIR__.'/Base/OpenCollectionTests.php';
require_once __DIR__.'/Base/Bill/TransactionTests.php';
require_once __DIR__.'/Base/Collection/PaymentMethodTests.php';

pest()->extend(TestCase::class)->in('.');

function billplz_register_tests(array $tests, array $hooks = []): void
{
    foreach ($tests as $name => $test) {
        it($name, function () use ($name, $test, $hooks): void {
            if (array_key_exists($name, $hooks)) {
                $hooks[$name]->call($this);
            }

            $test->call($this);
        });
    }
}
