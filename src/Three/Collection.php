<?php

namespace Billplz\Three;

use Billplz\Base\Collection as Request;
use Laravie\Codex\Contracts\Response;

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
     * @param  array<string, mixed>  $optional
     */
    public function create(string $title, array $optional = []): Response
    {
        $files = [];
        $body = array_merge(compact('title'), $optional);

        if (isset($body['logo'])) {
            $files['logo'] = ltrim($body['logo'], '@');
            unset($body['logo']);
        }

        return $this->stream('POST', 'collections', [], $body, $files);
    }
}
