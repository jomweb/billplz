<?php

namespace Billplz\Two;

class Collection extends Request
{
    /**
     * Create a new collection.
     *
     * @param  string  $title
     * @param  array  $optional
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create($title, array $optional = [])
    {
        $body = array_merge(compact('title'), $optional);

        list($uri, $headers) = $this->endpoint('collections');

        return $this->client->send('POST', $uri, $headers, $body);
    }
}
