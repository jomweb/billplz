<?php

namespace Laravie\Codex\Common;

use BadMethodCallException;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

/**
 * @mixin \Psr\Http\Message\UriInterface
 */
class Endpoint implements \Laravie\Codex\Contracts\Endpoint
{
    /**
     * Base URL.
     *
     * @var \Psr\Http\Message\UriInterface
     */
    protected $uri;

    /**
     * Request query strings.
     *
     * @var array
     */
    protected $query = [];

    /**
     * Construct API Endpoint.
     *
     * @param  \Psr\Http\Message\UriInterface|string  $uri
     * @param  array<int, string>|string  $paths
     * @param  array<string, string|null>  $query
     */
    public function __construct($uri, $paths = [], array $query = [])
    {
        $paths = \is_null($paths) || $paths === '/' ? [] : $paths;

        $this->uri = $uri instanceof UriInterface
            ? $uri
            : $this->createUri($uri, (array) $paths);

        $this->createQueryFromUri($this->uri);
        $this->addQuery($query);
    }

    /**
     * Create instance of Uri.
     *
     * @param  array<int, string>  $paths
     */
    final protected function createUri(?string $url, array $paths): UriInterface
    {
        if (is_null($url)) {
            $url = '';
        }

        $path = implode('/', $paths);

        if (! empty($path)) {
            $url = rtrim($url, '/')."/{$path}";
        }

        return new Uri($url);
    }

    /**
     * Create from UriInterface.
     */
    final protected function createQueryFromUri(UriInterface $uri): void
    {
        $this->createQuery(trim($uri->getQuery(), '/'));
    }

    /**
     * Prepare query string.
     */
    protected function createQuery(string $query): void
    {
        if (empty($query)) {
            return;
        }

        foreach (explode('&', $query) as $pair) {
            if (strpos($pair, '=') >= 1) {
                [$key, $value] = explode('=', $pair, 2);

                $this->addQuery($key, urldecode($value));
            }
        }
    }

    /**
     * Add query string.
     *
     * @param  string|array<string, string|null>  $key
     * @return $this
     */
    public function addQuery($key, ?string $value = null)
    {
        if (\is_array($key)) {
            foreach ($key as $name => $content) {
                $this->addQuery($name, $content);
            }
        } else {
            $this->query[$key] = $value;
        }

        return $this;
    }

    /**
     * Get URI.
     */
    public function getUri(): ?string
    {
        return ! empty($this->uri->getHost())
            ? $this->uri->getScheme().'://'.$this->uri->getHost()
            : null;
    }

    /**
     * Get path(s).
     *
     * @return array<int, string>
     */
    public function getPath(): array
    {
        return explode('/', trim($this->uri->getPath(), '/'));
    }

    /**
     * Get query string(s).
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * Get URI instance.
     */
    public function get(): UriInterface
    {
        $this->withQuery(
            http_build_query($this->getQuery(), '', '&')
        );

        return $this->uri;
    }

    /**
     * Call method under \Psr\Http\Message\UriInterface.
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        if (! method_exists($this->uri, $method)) {
            throw new BadMethodCallException("Method [{$method}] doesn't exists.");
        }

        $result = $this->uri->{$method}(...$parameters);

        if (strpos($method, 'with') !== 0) {
            return $result;
        } elseif ($result instanceof UriInterface) {
            $this->uri = $result;
        }

        return $this;
    }

    /**
     * Return the string representation as a URI reference.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->get();
    }
}
