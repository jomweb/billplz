<?php

namespace Laravie\Codex\Common;

use Laravie\Codex\Contracts\Client as ClientContract;
use Laravie\Codex\Contracts\Endpoint as EndpointContract;
use Laravie\Codex\Contracts\Response as ResponseContract;
use Psr\Http\Message\ResponseInterface;

abstract class Request implements \Laravie\Codex\Contracts\Request
{
    /**
     * The Codex client.
     *
     * @var \Laravie\Codex\Contracts\Client
     */
    protected $client;

    /**
     * Create Endpoint instance.
     *
     * @param  array<int, string>|string  $path
     * @param  array<string, string>  $query
     */
    public static function to(string $uri, $path = [], array $query = []): EndpointContract
    {
        return new Endpoint($uri, $path, $query);
    }

    /**
     * Set Codex Client.
     *
     * @return $this
     */
    final public function setClient(ClientContract $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Resolve the responder class.
     */
    abstract protected function responseWith(ResponseInterface $message): ResponseContract;
}
