<?php

namespace Billplz\Three;

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

    /**
     * Create a new open collection.
     *
     * @param  string  $title
     * @param  string  $description
     * @param  \Money\Money|int  $amount
     * @param  array  $optional
     *
     * @return \Laravie\Codex\Response
     */
    public function createOpen($title, $description, $amount, array $optional = [])
    {
        $amount = $money->getAmount();
        $body = array_merge(compact('title', 'description', 'amount'), $optional);

        return $this->send('POST', 'open_collections', [], $body);
    }
}
