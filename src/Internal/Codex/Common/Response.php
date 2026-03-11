<?php

namespace Laravie\Codex\Common;

use BadMethodCallException;
use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Exceptions\HttpException;
use Laravie\Codex\Exceptions\NotFoundException;
use Laravie\Codex\Exceptions\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;

/**
 * @mixin \Psr\Http\Message\ResponseInterface
 */
class Response implements \Laravie\Codex\Contracts\Response
{
    /**
     * The original response.
     */
    protected ResponseInterface $message;

    /**
     * Construct a new response.
     */
    public function __construct(ResponseInterface $message)
    {
        $this->message = $message;
    }

    /**
     * Validate the response object.
     *
     * @return $this
     */
    public function validate(): self
    {
        $this->abortIfRequestUnauthorized();

        return $this;
    }

    /**
     * Validate response with custom callable.
     *
     * @return $this
     */
    final public function validateWith(callable $callback): self
    {
        \call_user_func($callback, $this->getStatusCode(), $this);

        return $this;
    }

    /**
     * Validate response with custom callable.
     *
     * @return $this
     */
    final public function then(callable $callback): self
    {
        \call_user_func($callback, $this, $this->getStatusCode());

        return $this;
    }

    /**
     * Convert response body to array.
     */
    public function toArray(): array
    {
        $content = $this->getContent();

        if (\is_array($content)) {
            return $this instanceof Filterable
                ? (array) $this->filterResponse($content)
                : $content;
        }

        return [];
    }

    /**
     * Get body.
     */
    public function getBody(): string
    {
        return (string) $this->message->getBody();
    }

    /**
     * Get content from body, by default we assume it returning JSON.
     */
    public function getContent(): mixed
    {
        return \json_decode($this->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Get status code.
     */
    public function getStatusCode(): int
    {
        return $this->message->getStatusCode();
    }

    /**
     *  Gets the response reason phrase associated with the status code.
     */
    public function getReasonPhrase(): string
    {
        return $this->message->getReasonPhrase();
    }

    /**
     * Check if response is unauthorized.
     */
    public function isSuccessful(): bool
    {
        return \in_array($this->getStatusCode(), [200, 201, 202, 204, 205]);
    }

    /**
     * Check if response is missing.
     */
    public function isNotFound(): bool
    {
        return \in_array($this->getStatusCode(), [404]);
    }

    /**
     * Check if response is unauthorized.
     */
    public function isUnauthorized(): bool
    {
        return \in_array($this->getStatusCode(), [401, 403]);
    }

    /**
     * Validate for unauthorized request.
     *
     *
     * @throws \Laravie\Codex\Exceptions\UnauthorizedException
     */
    public function abortIfRequestUnauthorized(): void
    {
        if ($this->isUnauthorized()) {
            throw new UnauthorizedException($this);
        }
    }

    /**
     * Validate for unauthorized request.
     *
     *
     * @throws \Laravie\Codex\Exceptions\HttpException
     */
    public function abortIfRequestHasFailed(?string $message = null): void
    {
        $statusCode = $this->getStatusCode();

        if ($statusCode >= 400 && $statusCode < 600) {
            throw new HttpException($this, $message);
        }
    }

    /**
     * Abort if request data is not found.
     */
    public function abortIfRequestNotFound(?string $message = null): void
    {
        if ($this->isNotFound()) {
            throw new NotFoundException($this, $message);
        }
    }

    /**
     * Call method under \Psr\Http\Message\ResponseInterface.
     */
    public function __call(string $method, array $parameters): mixed
    {
        if (! method_exists($this->message, $method)) {
            throw new BadMethodCallException("Method [{$method}] doesn't exists.");
        }

        return $this->message->{$method}(...$parameters);
    }

    /**
     * Get hidden property.
     */
    public function __get(string $key): mixed
    {
        if (! property_exists($this, $key)) {
            return null;
        }

        return $this->{$key};
    }
}
