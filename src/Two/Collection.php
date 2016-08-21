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
     * @return \Laravie\Codex\Response
     */
    public function create($title, array $optional = [])
    {
        $body = array_merge(compact('title'), $optional);

        return $this->send('POST', 'collections', [], $body);
    }
}
