<?php

namespace Laravie\Codex\Contracts;

use Laravie\Codex\Common\Payload;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

interface Client
{
    /**
     * Send the HTTP request.
     *
     * @param  StreamInterface|Payload|array|null  $body
     */
    public function send(string $method, Endpoint $uri, array $headers = [], mixed $body = []): ResponseInterface;

    /**
     * Stream (multipart) the HTTP request.
     */
    public function stream(string $method, Endpoint $uri, array $headers, StreamInterface $stream): ResponseInterface;
}
