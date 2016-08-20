<?php

namespace Billplz\Two;

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
        array $optional = []
    ) {
        $amount = $money->getAmount();

        if (empty($email) && empty($mobile)) {
            throw new InvalidArgumentException('Either $email or $mobile should be present');
        }

        $body = array_merge(
            compact('email', 'mobile', 'name', 'amount'),
            $optional
        );

        $body['collection_id'] = $collectionId;
        $body['callback_url'] = $callbackUrl;

        list($uri, $headers) = $this->endpoint('bills');

        return $this->client->send('POST', $uri, $headers, $body);
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
        list($uri, $headers) = $this->endpoint("bills/{$id}");

        return $this->client->send('GET', $uri, $headers);
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
        list($uri, $headers) = $this->endpoint("bills/{$id}");

        return $this->client->send('DELETE', $uri, $headers);
    }
}
