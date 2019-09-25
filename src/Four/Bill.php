<?php

namespace Billplz\Four;

use Billplz\Three\Bill as Request;
use Laravie\Codex\Contracts\Response;

class Bill extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v4';

    /**
     * Create a new bill.
     *
     * @param  string  $collectionId
     * @param  string|null  $email
     * @param  string|null  $mobile
     * @param  string  $name
     * @param  \Money\Money|\Duit\MYR|int  $amount
     * @param  array|string  $callbackUrl
     * @param  string  $description
     * @param  array  $optional
     *
     * @throws \InvalidArgumentException
     *
     * @return \Billplz\Response
     */
    public function create(
        string $collectionId,
        ?string $email,
        ?string $mobile,
        string $name,
        $amount,
        $callbackUrl,
        string $description,
        array $optional = []
    ): Response {
        $parameters = func_get_args();

        return $this->proxyRequestViaVersion('v3', function () use ($parameters) {
            return parent::create(...$parameters);
        });
    }


    /**
     * Show an existing bill.
     *
     * @param  string  $id
     *
     * @return \Billplz\Response
     */
    public function get(string $id): Response
    {
        return $this->proxyRequestViaVersion('v3', function () use ($id) {
            return parent::get($id);
        });
    }

    /**
     * Show an existing bill transactions.
     *
     * @param  string  $id
     * @param  array   $optional
     *
     * @return \Billplz\Response
     */
    public function transaction(string $id, array $optional = []): Response
    {
        return $this->proxyRequestViaVersion('v3', function () use ($id, $optional) {
            return parent::transaction($id, $optional);
        });
    }


    /**
     * Destroy an existing bill.
     *
     * @param  string  $id
     *
     * @return \Billplz\Response
     */
    public function destroy(string $id): Response
    {
        return $this->proxyRequestViaVersion('v3', function () use ($id) {
            return parent::destroy($id);
        });
    }
}
