<?php

namespace Billplz;

use BadMethodCallException;

class Response
{
    /**
     * The original response.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $original;

    /**
     * Construct a new response.
     *
     * @param \Psr\Http\Message\ResponseInterface  $original
     */
    public function __construct($original)
    {
        $this->original = $original;
    }

    /**
     * Convert response body to array.
     *
     * @return array
     */
    public function toArray()
    {
        return json_decode($this->original->getBody(), true);
    }

    /**
     * Call method under \Psr\Http\Message\ResponseInterface.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (! method_exists($this->original, $method)) {
            throw new BadMethodCallException("Method [{$method}] doesn't exists.");
        }

        return call_user_func_array([$this->original, $method], $parameters);
    }
}
