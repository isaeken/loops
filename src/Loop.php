<?php

namespace IsaEken\Loops;

use Closure;
use IsaEken\Loops\Contracts\LoopCallback;
use IsaEken\Loops\Exceptions\NotWorkedException;
use Stringable;

class Loop implements Stringable
{
    /**
     * The index model of current loop.
     *
     * @var Index
     */
    private Index $index;

    /**
     * To check if this variable loop is working.
     *
     * @var bool
     */
    private bool $run = true;

    /**
     * To check the loop is worked correctly.
     *
     * @var bool
     */
    private bool $worked = false;

    /**
     * Loop last results for serialization.
     *
     * @var array
     */
    private array $results = [];

    /**
     * @param int $length Count of loop indexes.
     * @param LoopCallback|Closure|null $callback Closure to be called every loop.
     */
    public function __construct(public int $length, public LoopCallback|Closure|null $callback = null)
    {
        $this->index = new Index([
            'iteration' => 1,
            'index' => 0,
            'remaining' => $this->length - 1 ?? 0,
            'count' => $this->length,
            'first' => true,
            'last' => $this->length === 1,
            'even' => true,
            'odd' => false,
        ]);
    }

    /**
     * Increment the loop.
     */
    public function increment(): void
    {
        $this->index->iteration += 1;
        $this->index->index += 1;
        $this->index->remaining -= 1;
        $this->index->first = $this->index->index === 0;
        $this->index->last = $this->index->iteration === $this->index->count;
        $this->index->even = $this->index->index % 2 == 0;
        $this->index->odd = ! $this->index->even;
    }

    /**
     * Run the loop.
     *
     * @return array
     */
    public function run(): array
    {
        $this->worked = false;
        $this->results = [];

        for ($index = 0; $index < $this->length; $index++) {
            if ($this->callback === null) {
                $this->results[] = null;
            } elseif ($this->callback instanceof LoopCallback) {
                $this->results[] = call_user_func($this->callback, clone $this->index, $this);
            } else {
                $this->results[] = $this->callback->call($this, clone $this->index, $this);
            }

            $this->increment();

            if (! $this->run) {
                break;
            }
        }

        $this->worked = true;

        return $this->results;
    }

    /**
     * Break loop.
     * @deprecated Renamed to break()
     */
    public function stop(): void
    {
        $this->run = false;
    }

    /**
     * Break loop after current closure.
     *
     * @return void
     */
    public function break(): void
    {
        $this->run = false;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        if (! $this->worked) {
            throw new NotWorkedException();
        }

        return $this->results;
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
