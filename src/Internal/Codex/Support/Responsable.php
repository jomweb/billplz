<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Contracts\Response;
use Psr\Http\Message\ResponseInterface;

trait Responsable
{
    /**
     * Interacts with Response.
     */
    protected function interactsWithResponse(Response $response): Response
    {
        if ($response instanceof Filterable && $this instanceof Filterable) {
            $response->setFilterable($this->getFilterable());
        }

        if ($this->validateResponseAutomatically === true) {
            $response->validate();
        }

        return $response;
    }

    /**
     * Resolve the responder class.
     */
    abstract protected function responseWith(ResponseInterface $message): Response;
}
