<?php

namespace Laravie\Codex\Contracts;

use Psr\Http\Message\UriInterface;

interface Endpoint
{
    /**
     * Add query string.
     *
     * @param  string|array<string, string>  $key
     * @return $this
     */
    public function addQuery($key, ?string $value = null);

    /**
     * Get URI.
     */
    public function getUri(): ?string;

    /**
     * Get path(s).
     */
    public function getPath(): array;

    /**
     * Get query string(s).
     */
    public function getQuery(): array;

    /**
     * Get URI instance.
     */
    public function get(): UriInterface;
}
