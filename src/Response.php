<?php

namespace Billplz;

use BadMethodCallException;
use Psr\Http\Message\ResponseInterface;

class Response
{
    use WithSanitizer;

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
     * @param  \Billplz\Sanitizer|null  $sanitizer
     */
    public function __construct(ResponseInterface $original, Sanitizer $sanitizer = null)
    {
        $this->original = $original;

        $this->setSanitizer($sanitizer);
    }

    /**
     * Convert response body to array.
     *
     * @return array
     */
    public function toArray()
    {
        $body = json_decode($this->original->getBody(), true);

        if (! $this->hasSanitizer()) {
            return $body;
        }

        return $this->sanitizer->to($body);
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
