<?php

namespace Billplz;

use Billplz\Casts\Cast;

class Sanitizer
{
    /**
     * Sanitization rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Construct a new sanitizer.
     */
    public function __construct()
    {
        $this->casts = [
            'amount'      => new Casts\Money(),
            'due_at'      => new Casts\DateTime(),
            'paid_amount' => new Casts\Money(),
        ];
    }

    /**
     * Add sanitization rules.
     *
     * @param  string  $name
     * @param  \Billplz\Casts\Cast  $cast
     *
     * @return $this
     */
    public function add($name, Cast $cast)
    {
        $this->casts[$name] = $cast;

        return $this;
    }

    /**
     * Sanitize request.
     *
     * @param  array  $inputs
     *
     * @return array
     */
    public function from(array $inputs)
    {
        $data = [];

        foreach ($inputs as $name => $input) {
            $data[$name] = $this->sanitizeFrom($input, $name);
        }

        return $data;
    }

    /**
     * Sanitize response.
     *
     * @param  array  $inputs
     *
     * @return array
     */
    public function to(array $inputs)
    {
        $data = [];

        foreach ($inputs as $name => $input) {
            $data[$name] = $this->sanitizeTo($input, $name);
        }

        return $data;
    }

    /**
     * Sanitize input from.
     *
     * @param  mixed  $value
     * @param  string  $name
     *
     * @return mixed
     */
    protected function sanitizeFrom($value, $name)
    {
        if (! $this->hasCaster($name)) {
            return $value;
        }

        return $this->getCaster($name)->from($value);
    }

    protected function sanitizeTo($value, $name)
    {
        if (! $this->hasCaster($name)) {
            return $value;
        }

        return $this->getCaster($name)->to($value);
    }

    /**
     * Has caster.
     *
     * @param  string  $name
     *
     * @return bool
     */
    protected function hasCaster($name)
    {
        return isset($this->casts[$name]);
    }

    /**
     * Get caster.
     *
     * @param  string  $name
     *
     * @return \Billplz\Casts\Cast
     */
    protected function getCaster($name)
    {
        return $this->casts[$name];
    }
}
