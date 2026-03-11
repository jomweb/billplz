<?php

namespace Laravie\Codex\Exceptions;

use Exception;
use Http\Client\Exception as HttpClientException;
use InvalidArgumentException;
use Laravie\Codex\Contracts\Response;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class HttpException extends RuntimeException implements HttpClientException
{
    /**
     * Response headers.
     */
    protected ResponseInterface|Response $response;

    /**
     * Construct a new HTTP exception.
     */
    public function __construct(
        ResponseInterface|Response $response,
        ?string $message = null,
        ?Exception $previous = null,
        int $code = 0
    ) {
        $this->setResponse($response);

        parent::__construct(
            $message ?: $response->getReasonPhrase(),
            ($code > 0) ? $code : $response->getStatusCode(),
            $previous
        );
    }

    /**
     * Get status code.
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get response object.
     */
    public function getResponse(): ResponseInterface|Response
    {
        return $this->response;
    }

    /**
     * Set response object.
     *
     * @param  \Psr\Http\Message\ResponseInterface|\Laravie\Codex\Contracts\Response  $response
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setResponse(mixed $response): self
    {
        if (! $response instanceof ResponseInterface && ! $response instanceof Response) {
            throw new InvalidArgumentException(
                'The response must be an instance of Psr\Http\Message\ResponseInterface or Laravie\\Codex\\Contracts\\Response'
            );
        }

        $this->response = $response;

        return $this;
    }
}
