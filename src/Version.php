<?php

namespace Billplz;

abstract class Version
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version;

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
