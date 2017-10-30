<?php

namespace Billplz\Three;

class Bank extends Request
{
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
