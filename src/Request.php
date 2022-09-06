<?php

namespace Billplz;

use Laravie\Codex\Contracts\Endpoint;
use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Contracts\Response as ResponseContract;
use Laravie\Codex\Filter\WithSanitizer;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \Billplz\Client $client
 */
abstract class Request extends \Laravie\Codex\Request implements Filterable
{
    use WithSanitizer;

    /**
     * Get URI Endpoint.
     *
     * @param  array<int, string>|string  $path
     */
    protected function getApiEndpoint($path = []): Endpoint
    {
        $paths = is_array($path) ? $path : [$path];

        array_unshift($paths, $this->getVersion());

        return parent::getApiEndpoint($paths)
            ->withUserInfo($this->client->getApiKey());
    }

    /**
     * Resolve the responder class.
     */
    protected function responseWith(ResponseInterface $message): ResponseContract
    {
        return new Response($message);
    }

    /**
     * Resolve the sanitizer class.
     *
     * @return \Billplz\Sanitizer
     */
    protected function sanitizeWith(): Sanitizer
    {
        return new Sanitizer();
    }
}
