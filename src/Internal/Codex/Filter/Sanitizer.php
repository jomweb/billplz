<?php

namespace Laravie\Codex\Filter;

use Laravie\Codex\Contracts\Cast as CastContract;
use Laravie\Codex\Contracts\Sanitizer as SanitizerContract;

class Sanitizer implements SanitizerContract
{
    /**
     * Sanitization rules.
     */
    protected array $casts = [];

    /**
     * Add sanitization rules.
     *
     * @return $this
     */
    public function add(string|array $group, CastContract $cast): self
    {
        $this->casts = $this->setNestedValue($this->casts, (array) $group, $cast);

        return $this;
    }

    /**
     * Sanitize request.
     */
    public function from(array $inputs, array $group = []): array
    {
        $data = [];

        foreach ($inputs as $name => $input) {
            $data[$name] = $this->sanitizeFrom($input, $name, $group);
        }

        return $data;
    }

    /**
     * Sanitize response.
     */
    public function to(array $inputs, array $group = []): array
    {
        $data = [];

        foreach ($inputs as $name => $input) {
            $data[$name] = $this->sanitizeTo($input, $name, $group);
        }

        return $data;
    }

    /**
     * Sanitize request from.
     */
    protected function sanitizeFrom(mixed $value, string $name, array $group = []): mixed
    {
        array_push($group, $name);

        $caster = $this->resolveCaster($group);

        if (\is_array($value) && \is_null($caster)) {
            return $this->from($value, $group);
        }

        return ! \is_null($caster)
            ? $caster->from($value)
            : $value;
    }

    /**
     * Sanitize response to.
     */
    protected function sanitizeTo(mixed $value, string $name, array $group = []): mixed
    {
        array_push($group, $name);

        $caster = $this->resolveCaster($group);

        if (\is_array($value) && \is_null($caster)) {
            return $this->to($value, $group);
        }

        return ! \is_null($caster)
            ? $caster->to($value)
            : $value;
    }

    /**
     * Get caster.
     */
    protected function resolveCaster(string|array $group): ?CastContract
    {
        $cast = $this->getNestedValue($this->casts, (array) $group);

        if (is_subclass_of($cast, CastContract::class)) {
            return \is_string($cast) ? new $cast : $cast;
        }

        return null;
    }

    /**
     * Set a nested value using a path of keys.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, string>  $keys
     * @return array<string, mixed>
     */
    protected function setNestedValue(array $data, array $keys, mixed $value): array
    {
        if ($keys === []) {
            return $data;
        }

        $key = array_shift($keys);

        if ($key === null) {
            return $data;
        }

        if ($keys === []) {
            $data[$key] = $value;

            return $data;
        }

        $existing = $data[$key] ?? [];
        $data[$key] = $this->setNestedValue(\is_array($existing) ? $existing : [], $keys, $value);

        return $data;
    }

    /**
     * Get a nested value using a path of keys.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, string>  $keys
     */
    protected function getNestedValue(array $data, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (! \is_array($data) || ! \array_key_exists($key, $data)) {
                return null;
            }

            $data = $data[$key];
        }

        return $data;
    }
}
