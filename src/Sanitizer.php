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
            'amount' => new Casts\Money(),
            'due_at' => new Casts\DateTime(),
            'paid_amount' => new Casts\Money(),
        ];
    }

    /**
     * Add sanitization rules.
     *
     * @param  string|array  $name
     * @param  \Billplz\Casts\Cast  $cast
     *
     * @return $this
     */
    public function add($group, Cast $cast)
    {
        $this->casts = \igorw\assoc_in($this->casts, (array) $group, $cast);

        return $this;
    }

    /**
     * Sanitize request.
     *
     * @param  array  $inputs
     * @param  array  $group
     *
     * @return array
     */
    public function from(array $inputs, array $group = [])
    {
        $data = [];

        foreach ($inputs as $name => $input) {
            $data[$name] = $this->sanitizeFrom($input, $name, $group = []);
        }

        return $data;
    }

    /**
     * Sanitize response.
     *
     * @param  array  $inputs
     * @param  array  $group
     *
     * @return array
     */
    public function to(array $inputs, $group = [])
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
     * @param  string  $name
     * @param  array  $group
     *
     * @return mixed
     */
    protected function sanitizeFrom($value, $name, array $group = [])
    {
        array_push($group, $name);

        if (is_array($value)) {
            return $this->from($value, $group);
        }

        $caster = $this->getCaster($group);

        if (is_null($caster)) {
            return $value;
        }

        return $caster->from($value);
    }

    /**
     * Sanitize response to.
     *
     * @param  mixed  $value
     * @param  string  $name
     * @param  array  $group
     *
     * @return mixed
     */
    protected function sanitizeTo($value, $name, array $group = [])
    {
        array_push($group, $name);

        if (is_array($value)) {
            return $this->to($value, $group);
        }

        $caster = $this->getCaster($group);

        if (is_null($caster)) {
            return $value;
        }

        return $caster->to($value);
    }

    /**
     * Get caster.
     *
     * @param  string|array  $group
     *
     * @return \Billplz\Casts\Cast
     */
    protected function getCaster($group)
    {
        return \igorw\get_in($this->casts, (array) $group);
    }
}
