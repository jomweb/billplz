<?php

namespace Laravie\Codex\Concerns\Request;

use Laravie\Codex\Common\Payload;
use Laravie\Codex\Contracts\Endpoint;
use Laravie\Codex\Contracts\Response;
use Psr\Http\Message\StreamInterface;

trait Json
{
    /**
     * Send API request.
     *
     * @param  Endpoint|string  $path
     * @param  array<string, mixed>  $headers
     * @param  Payload|array|null  $body
     */
    protected function sendJson(string $method, $path, array $headers = [], $body = []): Response
    {
        $headers['Content-Type'] = 'application/json';

        return $this->send($method, $path, $headers, $body);
    }

    /**
     * Send API request.
     *
     * @param  Endpoint|string  $path
     * @param  array<string, mixed>  $headers
     * @param  StreamInterface|Payload|array|null  $body
     */
    abstract protected function send(string $method, $path, array $headers = [], $body = []): Response;
}
