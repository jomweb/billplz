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
     *
     * @var \Psr\Http\Message\ResponseInterface|\Laravie\Codex\Contracts\Response
     */
    protected $response;

    /**
     * Construct a new HTTP exception.
     *
     * @param  \Psr\Http\Message\ResponseInterface|\Laravie\Codex\Contracts\Response  $response
     */
    public function __construct(
        $response,
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
     *
     * @return \Psr\Http\Message\ResponseInterface|\Laravie\Codex\Contracts\Response
     */
    public function getResponse()
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
    final public function setResponse($response): self
    {
        if ($response instanceof Response || $response instanceof ResponseInterface) {
            $this->response = $response;

            return $this;
        }

        throw new InvalidArgumentException('$response is not an acceptable response object!');
    }
}
