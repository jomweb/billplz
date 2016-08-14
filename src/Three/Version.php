<?php

namespace Billplz\Three;

abstract class Version
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v3';

    /**
     * Get API endpoint.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function endpoint($name)
    {
        return sprintf('%s/%s', $this->version, $name);
    }
}
