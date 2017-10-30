<?php

namespace Billplz\Base;

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
     * @return object
     */
    public function massPayment()
    {
        return $this->client->resource('Collection.MassPayment', $this->getVersion());
    }
}
