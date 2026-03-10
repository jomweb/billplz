<?php

namespace Laravie\Codex\Contracts;

interface Filterable
{
    /**
     * Check if filterable exists.
     */
    public function hasFilterable(): bool;

    /**
     * Set filterable.
     */
    public function setFilterable(?Sanitizer $filterable): void;

    /**
     * Get filterable.
     */
    public function getFilterable(): ?Sanitizer;

    /**
     * Filter request content.
     *
     * @param  array|mixed  $content
     * @return mixed
     */
    public function filterRequest($content);

    /**
     * Filter response content.
     *
     * @param  array|mixed  $content
     * @return mixed
     */
    public function filterResponse($content);
}
