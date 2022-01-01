<?php

namespace IsaEken\Loops;

use Stringable;

/**
 * @property int $iteration
 * @property int $index
 * @property int $remaining
 * @property int $count
 * @property bool $first
 * @property bool $last
 * @property bool $even
 * @property bool $odd
 */
class Index implements Stringable
{
    /**
     * Model attribute pool.
     *
     * @var array $attributes
     */
    protected array $attributes = [];

    /**
     * Set attribute value.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setAttribute(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get attribute value.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute(string $name, mixed $default = null): mixed
    {
        if (!array_key_exists($name, $this->attributes)) {
            return $default;
        }

        return $this->attributes[$name];
    }

    /**
     * Fill attributes from array.
     *
     * @param array $attributes
     * @return void
     */
    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Create the Index instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function __set(string $name, $value): void
    {
        $this->setAttribute($name, $value);
    }

    public function __get(string $name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Get the instance as a json.
     *
     * @return false|string
     */
    public function toJson(): false|string
    {
        return json_encode($this->toArray());
    }

    /**
     * Get the instance as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
