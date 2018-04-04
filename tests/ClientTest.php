<?php

namespace Billplz\TestCase;

use Billplz\Client;
use Laravie\Codex\Discovery;
use Laravie\Codex\Testing\FakeRequest;

class ClientTest extends TestCase
{
    /** @test */
    public function it_can_be_initiated_directly()
    {
        $faker = FakeRequest::create();

        $client = new Client($faker->http(), '73eb57f0-7d4e-42b9-a544-aeac6e4b0f81', 'billplz');

        $this->assertSame('73eb57f0-7d4e-42b9-a544-aeac6e4b0f81', $client->getApiKey());
        $this->assertSame('billplz', $client->getSignatureKey());
        $this->assertSame('https://www.billplz.com/api', $client->getApiEndpoint());
    }

    /** @test */
    public function it_can_be_initiated_via_make()
    {
        $faker = FakeRequest::create();

        Discovery::override($faker->http());

        $client = Client::make('73eb57f0-7d4e-42b9-a544-aeac6e4b0f81', 'billplz');

        $this->assertSame('73eb57f0-7d4e-42b9-a544-aeac6e4b0f81', $client->getApiKey());
        $this->assertSame('billplz', $client->getSignatureKey());
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
    public function it_can_retrieve_bill_instance()
    {
        $client = $this->makeClient();

        $bill = $client->bill('v3');

        $this->assertInstanceOf('Billplz\Base\Bill', $bill);
        $this->assertInstanceOf('Billplz\Three\Bill', $bill);
    }

    /** @test */
    public function it_can_retrieve_check_instance()
    {
        $client = $this->makeClient();

        $check = $client->check('v3');

        $this->assertInstanceOf('Billplz\Base\Check', $check);
        $this->assertInstanceOf('Billplz\Three\Check', $check);
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

        $this->assertInstanceOf('Billplz\Base\Bank', $bank);
        $this->assertInstanceOf('Billplz\Three\Bank', $bank);
    }
}
