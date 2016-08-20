<?php

namespace Billplz\Three;

use Money\Money;
use InvalidArgumentException;

class Bill extends Request
{
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
     * @throws  \InvalidArgumentException
     *
     * @return \Psr\Http\Message\ResponseInterface
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

        return $this->send('POST', 'bills', [], $body);
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
        return $this->send('GET', "bills/{$id}");
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
        return $this->send('DELETE', "bills/{$id}");
    }
}
