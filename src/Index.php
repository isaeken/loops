<?php

namespace IsaEken\Loops;

class Index
{
    public int $iteration;

    public int $index;

    public int $remaining;

    public int $count;

    public bool $first;

    public bool $last;

    public bool $odd;

    public bool $even;

    public function setAttribute(string $name, mixed $value): void
    {
        $this->$name = $value;
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        if (!property_exists($this, $name)) {
            return $default;
        }

        return $this->$name;
    }

    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }
}
