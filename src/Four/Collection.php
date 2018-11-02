<?php

namespace Billplz\Four;

use Laravie\Codex\Contracts\Response;
use Billplz\Base\Collection as Request;

class Collection extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v4';

    /**
     * Activate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function activate(string $id): Response
    {
        return $this->proxyRequestUsingVersion('v3', function () use ($id) {
            return parent::activate($id);
        });
    }

    /**
     * Deactivate a collection.
     *
     * @param  string  $id
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function deactivate(string $id): Response
    {
        return $this->proxyRequestUsingVersion('v3', function () use ($id) {
            return parent::deactivate($id);
        });
    }

    /**
     * Get mass payment instruction collection resource.
     *
     * @return \Billplz\Four\Collection\MassPayment
     */
    public function massPayment(): Collection\MassPayment
    {
        return $this->client->uses('Collection.MassPayment', $this->getVersion());
    }
}
