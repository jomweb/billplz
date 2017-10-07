<?php

namespace Billplz\Three;

use Billplz\Request as BaseRequest;

abstract class Request extends BaseRequest
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v4';

    /**
     * Parse callback URL from request.
     *
     * @param  array  $body
     * @param  array|string  $url
     *
     * @return array
     */
    protected function parseRedirectAndCallbackUrlFromRequest(array $body, $url)
    {
        if (is_string($url)) {
            $body['callback_url'] = $url;
        } elseif (is_array($url)) {
            $body['callback_url'] = isset($url['callback_url']) ? $url['callback_url'] : null;
            $body['redirect_url'] = isset($url['redirect_url']) ? $url['redirect_url'] : null;
        }

        return $body;
    }
}
