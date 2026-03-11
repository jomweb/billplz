<?php

namespace Laravie\Codex\Filter;

use Laravie\Codex\Contracts\Sanitizer;

trait WithSanitizer
{
    /**
     * The filterable implementation.
     *
     * @var \Laravie\Codex\Contracts\Sanitizer|null
     */
    protected $filterable;

    /**
     * Check if filterable exists.
     */
    final public function hasFilterable(): bool
    {
        return $this->filterable instanceof Sanitizer;
    }

    /**
     * Set filterable.
     */
    final public function setFilterable(?Sanitizer $filterable = null): void
    {
        $this->filterable = $filterable;
    }

    /**
     * Get sanitizer.
     */
    final public function getFilterable(): ?Sanitizer
    {
        return $this->filterable;
    }

    /**
     * Filter request content.
     *
     * @param  array|mixed  $content
     */
    final public function filterRequest(mixed $content): mixed
    {
        return $this->hasFilterable() && \is_array($content)
            ? $this->filterable->from($content)
            : $content;
    }

    /**
     * Filter response content.
     *
     * @param  array|mixed  $content
     */
    final public function filterResponse(mixed $content): mixed
    {
        return $this->hasFilterable() && \is_array($content)
            ? $this->filterable->to($content)
            : $content;
    }
}
