<?php

namespace Laravie\Codex\Filter;

use Laravie\Codex\Contracts\Cast as CastContract;
use Laravie\Codex\Contracts\Sanitizer as SanitizerContract;

class Sanitizer implements SanitizerContract
{
    /**
     * Sanitization rules.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Add sanitization rules.
     *
     * @param  string|array  $group
     * @return $this
     */
    public function add($group, CastContract $cast)
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
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function sanitizeFrom($value, string $name, array $group = [])
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
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function sanitizeTo($value, string $name, array $group = [])
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
     *
     * @param  string|array  $group
     */
    protected function resolveCaster($group): ?CastContract
    {
        $cast = $this->getNestedValue($this->casts, (array) $group);

        if (is_subclass_of($cast, CastContract::class)) {
            return \is_string($cast) ? new $cast() : $cast;
        }

        return null;
    }

    /**
     * Set a nested value using a path of keys.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, string>  $keys
     * @param  mixed  $value
     * @return array<string, mixed>
     */
    protected function setNestedValue(array $data, array $keys, $value): array
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
     * @return mixed
     */
    protected function getNestedValue(array $data, array $keys)
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
