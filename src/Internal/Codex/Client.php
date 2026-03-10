<?php

namespace Laravie\Codex;

use InvalidArgumentException;

abstract class Client implements Contracts\Client
{
    use Common\HttpClient;

    /**
     * The API endpoint.
     *
     * @var string
     */
    protected $apiEndpoint;

    /**
     * Default API version.
     *
     * @var string
     */
    protected $defaultVersion = 'v1';

    /**
     * List of supported API versions.
     *
     * @var array<string, string>
     */
    protected $supportedVersions = [];

    /**
     * Dump HTTP requests for the client.
     */
    public function queries(): array
    {
        return $this->httpRequestQueries;
    }

    /**
     * Use custom API Endpoint.
     *
     *
     * @return $this
     */
    public function useCustomApiEndpoint(string $endpoint)
    {
        $this->apiEndpoint = $endpoint;

        return $this;
    }

    /**
     * Use different API version.
     *
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function useVersion(string $version)
    {
        if (! \array_key_exists($version, $this->supportedVersions)) {
            throw new InvalidArgumentException("API version [{$version}] is not supported.");
        }

        $this->defaultVersion = $version;

        return $this;
    }

    /**
     * Get API endpoint URL.
     */
    public function getApiEndpoint(): ?string
    {
        return $this->apiEndpoint;
    }

    /**
     * Get API default version.
     */
    public function getApiVersion(): string
    {
        return $this->defaultVersion;
    }

    /**
     * Get versioned resource (service).
     *
     *
     *
     * @throws \InvalidArgumentException
     */
    public function uses(string $service, ?string $version = null): Contracts\Request
    {
        if (\is_null($version) || ! \array_key_exists($version, $this->supportedVersions)) {
            $version = $this->defaultVersion;
        }

        $name = str_replace('.', '\\', $service);

        /** @var class-string<\Laravie\Codex\Contracts\Request> $class */
        $class = sprintf('%s\%s\%s', $this->getResourceNamespace(), $this->supportedVersions[$version], $name);

        if (! class_exists($class)) {
            throw new InvalidArgumentException("Resource [{$service}] for version [{$version}] is not available.");
        }

        return $this->via(new $class($this));
    }

    /**
     * Handle uses using via.
     */
    public function via(Contracts\Request $request): Contracts\Request
    {
        $request->setClient($this);

        return $request;
    }

    /**
     * Prepare request headers.
     *
     * @param  array<string, mixed>  $headers
     * @return array<string, mixed>
     */
    protected function prepareRequestHeaders(array $headers = []): array
    {
        return $headers;
    }

    /**
     * Get resource default namespace.
     */
    abstract protected function getResourceNamespace(): string;
}
