<?php

namespace Billplz\Four;

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
     * Get mass payment instruction collection resource.
     *
     * @return \Billplz\Four\Collection\MassPayment
     */
    public function massPayment(): Collection\MassPayment
    {
        return $this->client->uses('Collection.MassPayment', $this->getVersion());
    }
}
