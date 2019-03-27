<?php

namespace Billplz\Tests;

use Billplz\Client;
use Laravie\Codex\Testing\Faker;
use Laravie\Codex\Discovery;

class ClientTest extends TestCase
{
    /** @test */
    public function it_can_be_initiated_directly()
    {
        $faker = Faker::create();

        $client = new Client($faker->http(), static::API_KEY, static::X_SIGNATURE);

        $this->assertSame(static::API_KEY, $client->getApiKey());
        $this->assertSame(static::X_SIGNATURE, $client->getSignatureKey());
        $this->assertSame('https://www.billplz.com/api', $client->getApiEndpoint());
    }

    /** @test */
    public function it_can_be_initiated_via_make()
    {
        $faker = Faker::create();

        Discovery::override($faker->http());

        $client = Client::make(static::API_KEY, static::X_SIGNATURE);

        $this->assertSame(static::API_KEY, $client->getApiKey());
        $this->assertSame(static::X_SIGNATURE, $client->getSignatureKey());
        $this->assertSame('https://www.billplz.com/api', $client->getApiEndpoint());
    }

    /** @test */
    public function it_can_use_sandbox_endpoint()
    {
        $client = $this->makeClient();

        $client->useSandbox();

        $this->assertSame('https://billplz-staging.herokuapp.com/api', $client->getApiEndpoint());
    }

    /** @test */
    public function it_can_retrieve_collection_instance()
    {
        $client = $this->makeClient();

        $collection = $client->collection('v3');

        $this->assertInstanceOf('Billplz\Base\Collection', $collection);
        $this->assertInstanceOf('Billplz\Three\Collection', $collection);
    }

    /** @test */
    public function it_can_retrieve_open_collection_instance()
    {
        $client = $this->makeClient();

        $collection = $client->openCollection('v3');

        $this->assertInstanceOf('Billplz\Base\OpenCollection', $collection);
        $this->assertInstanceOf('Billplz\Three\OpenCollection', $collection);
    }

    /** @test */
    public function it_can_retrieve_bill_instance()
    {
        $client = $this->makeClient();

        $bill = $client->bill('v3');

        $this->assertInstanceOf('Billplz\Base\Bill', $bill);
        $this->assertInstanceOf('Billplz\Three\Bill', $bill);
    }

    /** @test */
    public function it_can_retrieve_transaction_instance()
    {
        $client = $this->makeClient();

        $transaction = $client->transaction('v3');

        $this->assertInstanceOf('Billplz\Base\Bill\Transaction', $transaction);
        $this->assertInstanceOf('Billplz\Three\Bill\Transaction', $transaction);
    }

    /** @test */
    public function it_can_retrieve_mass_payment_collection_instance()
    {
        $client = $this->makeClient();

        $massPaymentCollection = $client->massPaymentCollection('v4');

        $this->assertInstanceOf('Billplz\Four\Collection\MassPayment', $massPaymentCollection);
    }

    /** @test */
    public function it_can_retrieve_mass_payment_instance()
    {
        $client = $this->makeClient();

        $massPayment = $client->massPayment('v4');

        $this->assertInstanceOf('Billplz\Four\MassPayment', $massPayment);
    }

    /** @test */
    public function it_can_retrieve_bank_instance()
    {
        $client = $this->makeClient();

        $bank = $client->bank('v3');

        $this->assertInstanceOf('Billplz\Base\BankAccount', $bank);
        $this->assertInstanceOf('Billplz\Three\BankAccount', $bank);
    }
}
