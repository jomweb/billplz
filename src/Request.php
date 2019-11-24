<?php

namespace Billplz;

use Laravie\Codex\Contracts\Endpoint;
use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Filter\WithSanitizer;
use Psr\Http\Message\ResponseInterface;
use Laravie\Codex\Contracts\Response as ResponseContract;

abstract class Request extends \Laravie\Codex\Request implements Filterable
{
    use WithSanitizer;

    /**
     * Get URI Endpoint.
     *
     * @param  array|string  $path
     */
    protected function getApiEndpoint($path = []): Endpoint
    {
        return parent::getApiEndpoint([$this->getVersion(), $path])
            ->withUserInfo($this->client->getApiKey());
    }

    /**
     * Resolve the responder class.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     *
     * @return \Billplz\Response
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

    /**
     * Parse callback URL from request.
     *
     * @param  array|string  $url
     */
    final protected function parseRedirectAndCallbackUrlFromRequest(array $body, $url): array
    {
        if (\is_string($url)) {
            $body['callback_url'] = $url;
        } elseif (\is_array($url)) {
            $body['callback_url'] = $url['callback_url'] ?? null;
            $body['redirect_url'] = $url['redirect_url'] ?? null;
        }

        return $body;
    }
}
