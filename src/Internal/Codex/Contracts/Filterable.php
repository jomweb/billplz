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
     */
    public function filterRequest(mixed $content): mixed;

    /**
     * Filter response content.
     *
     * @param  array|mixed  $content
     */
    public function filterResponse(mixed $content): mixed;
}
