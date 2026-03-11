<?php

namespace Laravie\Codex\Contracts;

/**
 * @method string getReasonPhrase()
 */
interface Response
{
    /**
     * Validate the response object.
     *
     * @return $this
     */
    public function validate(): self;

    /**
     * Convert response body to array.
     */
    public function toArray(): array;

    /**
     * Get body.
     */
    public function getBody(): mixed;

    /**
     * Get content from body, by default we assume it returning JSON.
     */
    public function getContent(): mixed;

    /**
     * Get status code.
     */
    public function getStatusCode(): int;
}
