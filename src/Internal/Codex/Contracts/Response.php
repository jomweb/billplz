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
    public function validate();

    /**
     * Convert response body to array.
     */
    public function toArray(): array;

    /**
     * Get body.
     *
     * @return mixed
     */
    public function getBody();

    /**
     * Get content from body, by default we assume it returning JSON.
     *
     * @return mixed
     */
    public function getContent();

    /**
     * Get status code.
     */
    public function getStatusCode(): int;
}
