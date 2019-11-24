<?php

namespace Billplz\Three;

use Laravie\Codex\Contracts\Response;
use Billplz\Base\Collection as Request;

class Collection extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v3';

    /**
     * Create a new collection.
     *
     * @return \Billplz\Response
     */
    public function create(string $title, array $optional = []): Response
    {
        $files = [];
        $body = \array_merge(\compact('title'), $optional);

        if (isset($body['logo'])) {
            $files['logo'] = \ltrim($body['logo'], '@');
            unset($body['logo']);
        }

        return $this->stream('POST', 'collections', [], $body, $files);
    }
}
