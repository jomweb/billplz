<?php

namespace Billplz\Four;

use Billplz\Base\Bill as Request;
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
     * @param  \Money\Money|\Duit\MYR|int  $amount
     * @param  array|string  $callbackUrl
     * @param  array<string, mixed>  $optional
     *
     * @throws \InvalidArgumentException
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
        $parameters = \func_get_args();

        return $this->proxyRequestViaVersion('v3', function () use ($parameters) {
            return parent::create(...$parameters);
        });
    }

    /**
     * Show an existing bill.
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
     * @param  array<string, mixed>  $optional
     */
    public function transaction(string $id, array $optional = []): Response
    {
        return $this->proxyRequestViaVersion('v3', function () use ($id, $optional) {
            return parent::transaction($id, $optional);
        });
    }

    /**
     * Destroy an existing bill.
     */
    public function destroy(string $id): Response
    {
        return $this->proxyRequestViaVersion('v3', function () use ($id) {
            return parent::destroy($id);
        });
    }

    /**
     * Bill payment using Visa/MasterCard card via generated token.
     */
    public function charge(string $id, string $cardId, string $cardToken): Response
    {
        return $this->send('POST', "bills/{$id}/charge", [], [
            'card_id' => $cardId,
            'token' => $cardToken,
        ]);
    }
}
