<?php

namespace Billplz;

use Laravie\Codex\Contracts\Endpoint;
use Psr\Http\Message\ResponseInterface;
use Laravie\Codex\Request as BaseRequest;
use Laravie\Codex\Contracts\Response as ResponseContract;

abstract class Request extends BaseRequest
{
    /**
     * Get URI Endpoint.
     *
     * @param  array|string  $path
     *
     * @return \Laravie\Codex\Contracts\Endpoint
     */
    protected function getApiEndpoint($path = []): Endpoint
    {
        $path = [$this->getVersion(), $path];

        return parent::getApiEndpoint($path)
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
     * @param  array  $body
     * @param  array|string  $url
     *
     * @return array
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
