<?php

namespace Billplz;

use Money\Money;
use InvalidArgumentException;

class Bill
{
    /**
     * The Billplz client.
     *
     * @var \Billplz\Client
     */
    protected $client;

    /**
     * Construct a new Collection.
     *
     * @param \Billplz\Client  $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new bill.
     *
     * @param  string  $collectionId
     * @param  string  $email
     * @param  string  $mobile
     * @param  string  $name
     * @param  \Money\Money  $money
     * @param  string  $callbackUrl
     * @param  string  $description
     * @param  array  $optional
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws  \InvalidArgumentException
     */
    public function create(
        $collectionId,
        $email,
        $mobile,
        $name,
        Money $money,
        $callbackUrl,
        $description,
        array $optional = []
    ) {
        $amount = $money->getAmount();

        if (empty($email) && empty($mobile)) {
            throw new InvalidArgumentException('Either $email or $mobile should be present');
        }

        $body = array_merge(
            compact('email', 'mobile', 'name', 'amount', 'description'),
            $optional
        );

        $body['collection_id'] = $collectionId;
        $body['callback_url'] = $callbackUrl;

        return $this->client->send('POST', 'bills', [], $body);
    }

    /**
     * Show an existing bill.
     *
     * @param  string  $id
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function show($id)
    {
        return $this->client->send('GET', "bills/{$id}");
    }

    /**
     * Destroy an existing bill.
     *
     * @param  string  $id
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function destroy($id)
    {
        return $this->client->send('DELETE', "bills/{$id}");
    }
}
