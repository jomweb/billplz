<?php

namespace Laravie\Codex\Contracts;

interface Sanitizer
{
    /**
     * Add sanitization rules.
     *
     * @return $this
     */
    public function add(string|array $group, Cast $cast): self;

    /**
     * Sanitize request.
     */
    public function from(array $inputs, array $group = []): array;

    /**
     * Sanitize response.
     */
    public function to(array $inputs, array $group = []): array;
}
