<?php

namespace Billplz\Three;

use InvalidArgumentException;

class Bill extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v3';

    /**
     * Create a new bill.
     *
     * @param  string  $collectionId
     * @param  string  $email
     * @param  string  $mobile
     * @param  string  $name
     * @param  \Money\Money|int  $amount
     * @param  array|string  $callbackUrl
     * @param  string  $description
     * @param  array  $optional
     *
     * @throws \InvalidArgumentException
     *
     * @return \Laravie\Codex\Response
     */
    public function create(
        $collectionId,
        $email,
        $mobile,
        $name,
        $amount,
        $callbackUrl,
        $description,
        array $optional = []
    ) {
        if (empty($email) && empty($mobile)) {
            throw new InvalidArgumentException('Either $email or $mobile should be present');
        }

        $body = array_merge(
            compact('email', 'mobile', 'name', 'amount', 'description'),
            $optional
        );

        $body['collection_id'] = $collectionId;

        $body = $this->parseRedirectAndCallbackUrlFromRequest($body, $callbackUrl);

        return $this->send('POST', 'bills', [], $body);
    }

    /**
     * Show an existing bill.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Response
     */
    public function show($id)
    {
        return $this->send('GET', "bills/{$id}");
    }

    /**
     * Show an existing bill transactions.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Response
     */
    public function transaction($id, array $optional = [])
    {
        return $this->client->resource('Bill.Transaction', $this->getVersion())
                    ->show($id, $optional);
    }

    /**
     * Destroy an existing bill.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Response
     */
    public function destroy($id)
    {
        return $this->send('DELETE', "bills/{$id}");
    }

    /**
     * Parse webhook data for a bill.
     *
     * @param  array  $data
     *
     * @return array
     */
    public function webhook(array $data = [])
    {
        return $this->sanitizeTo($data);
    }
}
