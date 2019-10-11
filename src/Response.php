<?php

namespace Billplz;

use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Filter\WithSanitizer;

class Response extends \Laravie\Codex\Response implements Filterable
{
    use WithSanitizer;

    /**
     * Validate the response object.
     *
     * @return $this
     */
    public function validate()
    {
        $this->abortIfRequestNotFound();
        $this->abortIfRequestUnauthorized();
        $this->abortIfRequestExceedLimiter();
        $this->abortIfRequestHasFailed();

        return $this;
    }

    /**
     * Validate for unauthorized request.
     *
     * @param  string|null  $message
     *
     * @throws \Billplz\Exceptions\ExceedRequestLimiter
     *
     * @return void
     */
    public function abortIfRequestExceedLimiter(?string $message = null): void
    {
        if (\in_array($this->getStatusCode(), [419])) {
            throw new Exceptions\ExceedRequestLimiter($this, $message);
        }
    }

    /**
     * Get rate limit (or return null for unlimited).
     *
     * @return int|null
     */
    public function rateLimit(): ?int
    {
        $value = $this->message->getHeaderLine('RateLimit-Limit');

        return ! \in_array($value, ['', 'unlimited'])
            ? (int) $value
            : null;
    }

    /**
     * Get remaining rate limit count (or return null for unlimited).
     *
     * @return int|null
     */
    public function remainingRateLimit(): ?int
    {
        $value = $this->message->getHeaderLine('RateLimit-Remaining');

        return ! \in_array($value, ['', 'unlimited'])
            ? (int) $value
            : null;
    }

    /**
     * Get next reset for rate limit (in seconds).
     *
     * @return int
     */
    public function rateLimitNextReset(): int
    {
        $value = $this->message->getHeaderLine('RateLimit-Reset');

        return ! \in_array($value, ['', 'unlimited'])
            ? (int) $value
            : 0;
    }
}
