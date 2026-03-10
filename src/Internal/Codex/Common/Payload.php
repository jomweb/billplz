<?php

namespace Laravie\Codex\Common;

use Psr\Http\Message\StreamInterface;

class Payload
{
    /**
     * Payload content.
     *
     * @var object|array|null
     */
    protected $content = null;

    /**
     * Construct a new payload.
     *
     * @param  object|array|null  $content
     */
    public function __construct($content = null)
    {
        $this->content = $content;
    }

    /**
     * Construct a new payload using static.
     *
     * @param  mixed  $content
     * @return static|self
     */
    public static function make($content = null)
    {
        if ($content instanceof self) {
            return $content;
        }

        return new static($content);
    }

    /**
     * Get payload content.
     *
     * @return mixed
     */
    public function get(array $headers = [])
    {
        if ($this->content instanceof StreamInterface) {
            return $this->content;
        }

        if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') {
            return $this->toJson();
        } elseif (\is_array($this->content)) {
            return $this->toHttpQueries();
        }

        return $this->content;
    }

    /**
     * Convert the content to JSON.
     *
     * @param  int  $options
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->content, $options);
    }

    /**
     * Convert the content to http queries.
     */
    public function toHttpQueries(?string $prefix = null, string $separator = '&'): string
    {
        return http_build_query($this->content ?? [], (string) $prefix, $separator);
    }
}
