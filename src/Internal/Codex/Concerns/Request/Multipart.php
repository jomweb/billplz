<?php

namespace Laravie\Codex\Concerns\Request;

use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\MultipartStream\MultipartStreamBuilder as Builder;
use Laravie\Codex\Contracts\Endpoint;
use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Contracts\Response;
use Psr\Http\Message\StreamInterface;

trait Multipart
{
    /**
     * Stream (multipart) the HTTP request.
     *
     * @param  \Laravie\Codex\Contracts\Endpoint|string  $path
     * @param  array<string, mixed>  $headers
     * @param  \Psr\Http\Message\StreamInterface|\Laravie\Codex\Payload|array|null  $body
     * @param  array<string, string>  $files
     */
    public function stream(string $method, $path, array $headers = [], $body = [], array $files = []): Response
    {
        $headers['Content-Type'] = 'multipart/form-data';

        $endpoint = $path instanceof Endpoint
            ? $this->getApiEndpoint($path->getPath())->addQuery($path->getQuery())
            : $this->getApiEndpoint($path);

        if ($body instanceof StreamInterface) {
            $stream = $body;
        } else {
            [$headers, $stream] = $this->prepareMultipartRequestPayloads(
                $headers, $body, $files
            );
        }

        $message = $this->responseWith(
            $this->client->stream($method, $endpoint, $headers, $stream)
        );

        return $this->interactsWithResponse($message);
    }

    /**
     * Prepare multipart request payloads.
     *
     * @param  array<string, mixed>  $headers
     * @param  array<string, string>  $files
     */
    final public function prepareMultipartRequestPayloads(array $headers = [], array $body = [], array $files = []): array
    {
        $multipart = (($headers['Content-Type'] ?? null) == 'multipart/form-data');

        if (empty($files) && ! $multipart) {
            return [$headers, $body];
        }

        $builder = new Builder(StreamFactoryDiscovery::find());

        $this->addFilesToMultipartBuilder($builder, $files);

        $this->addBodyToMultipartBuilder(
            $builder, $this instanceof Filterable ? $this->filterRequest($body) : $body
        );

        $headers['Content-Type'] = 'multipart/form-data; boundary='.$builder->getBoundary();

        return [$headers, $builder->build()];
    }

    /**
     * Add body to multipart stream builder.
     */
    final protected function addBodyToMultipartBuilder(Builder $builder, array $body, ?string $prefix = null): void
    {
        foreach ($body as $key => $value) {
            $name = $key;

            if (! \is_null($prefix)) {
                $name = "{$prefix}[{$key}]";
            }

            if (\is_array($value)) {
                $this->addBodyToMultipartBuilder($builder, $value, $name);

                continue;
            }

            $builder->addResource($name, $value, ['Content-Type' => 'text/plain']);
        }
    }

    /**
     * Add files to multipart stream builder.
     *
     * @param  array<string, string>  $files
     */
    final protected function addFilesToMultipartBuilder(Builder $builder, array $files = []): void
    {
        foreach ($files as $key => $file) {
            if (! \is_null($file)) {
                $builder->addResource($key, fopen($file, 'r'));
            }
        }
    }
}
