<?php

namespace Billplz\Four;

use Billplz\Base\Collection as Request;
use Laravie\Codex\Contracts\Response;

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
     */
    public function activate(string $id): Response
    {
        return $this->proxyRequestViaVersion('v3', function () use ($id) {
            return parent::activate($id);
        });
    }

    /**
     * Deactivate a collection.
     */
    public function deactivate(string $id): Response
    {
        return $this->proxyRequestViaVersion('v3', function () use ($id) {
            return parent::deactivate($id);
        });
    }

    /**
     * Get mass payment instruction collection resource.
     *
     * @return \Billplz\Four\Collection\Payout
     */
    public function payout(): Collection\Payout
    {
        return $this->client->uses('Collection.Payout', $this->getVersion());
    }
}
