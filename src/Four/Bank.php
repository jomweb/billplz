<?php

namespace Billplz\Four;

class Bank extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v3';

    /**
     * Get list of bank for Bank Direct Feature.
     *
     * @return \Laravie\Codex\Response
     */
    public function supportedForFpx()
    {
        return $this->send('GET', 'fpx_banks');
    }
}
