<?php

namespace Billplz;

trait WithSanitizer
{
    /**
     * The Sanitizer.
     *
     * @var \Billplz\Sanitizer
     */
    protected $sanitizer;

    /**
     * Check if sanitizaer exists.
     *
     * @return bool
     */
    public function hasSanitizer()
    {
        return $this->sanitizer instanceof Sanitizer;
    }

    /**
     * Set sanitizer.
     *
     * @param  \Billplz\Sanitizer|null  $sanitizer
     *
     * @return $this
     */
    public function setSanitizer(Sanitizer $sanitizer = null)
    {
        $this->sanitizer = $sanitizer;

        return $this;
    }

    /**
     * Get sanitizer.
     *
     * @return \Billplz\Sanitizer|null
     */
    public function getSanitizer()
    {
        return $this->sanitizer;
    }
}
