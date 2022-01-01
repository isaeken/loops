<?php

namespace IsaEken\Loops\Workers;

use Closure;
use IsaEken\Loops\Contracts\Arrayable;
use IsaEken\Loops\Contracts\Breakable;
use IsaEken\Loops\Contracts\Jsonable;
use IsaEken\Loops\Contracts\LoopCallback;
use IsaEken\Loops\Contracts\Looper;
use IsaEken\Loops\Contracts\Workable;
use IsaEken\Loops\Exceptions\NotWorkedException;
use IsaEken\Loops\Index;
use Opis\Closure\SerializableClosure;
use Spatie\Async\Pool;
use Stringable;

class AsyncWorker implements Workable, Breakable, Arrayable, Jsonable, Stringable
{
    /**
     * The Looper instance.
     *
     * @var Looper
     */
    private Looper $looper;

    /**
     * To check the loop is worker correctly.
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
     * Check the worker is breaked.
     *
     * @var bool
     */
    private bool $breaked = false;

    /**
     * The index model of current loop.
     *
     * @var Index|null
     */
    private Index|null $index = null;

    /**
     * @inheritDoc
     */
    public function getLooper(): Looper
    {
        return $this->looper;
    }

    /**
     * @inheritDoc
     */
    public function setLooper(Looper $looper): self
    {
        $this->looper = $looper;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function work(Closure|LoopCallback|SerializableClosure|null $callback = null): Pool
    {
        $pool = new Pool();
        $results = collect();

        for ($index = 0; $index < $this->getLooper()->getLength(); $index++) {
            $pool->add(function () use ($callback, $results, $pool) {
                if ($callback === null) {
                    $result = null;
                } elseif ($callback instanceof LoopCallback) {
                    $result = call_user_func($callback, clone $this->getIndex(), $this->getLooper());
                } elseif ($callback instanceof SerializableClosure) {
                    $result = $callback->getClosure()->call($this->getLooper(), clone $this->getIndex(), $this->getLooper());
                } else {
                    $result = $callback->call($this->getLooper(), clone $this->getIndex(), $this->getLooper());
                }

                $results->add($result);
                $this->increment();

                if ($this->breaked) {
                    $pool->stop();
                }

                return $result;
            })->then(function () use ($results) {
                $this->results = $results->toArray();
                $this->worked = true;
            });
        }

        return $pool;
    }

    /**
     * @inheritDoc
     */
    public function break(): self
    {
        $this->breaked = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function results(): array
    {
        if (! $this->worked) {
            throw new NotWorkedException();
        }

        return $this->results;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(): Index
    {
        if ($this->index === null) {
            $this->index = new Index([
                'iteration' => 1,
                'index' => 0,
                'remaining' => $this->getLooper()->getLength() - 1 ?? 0,
                'count' => $this->getLooper()->getLength(),
                'first' => true,
                'last' => $this->getLooper()->getLength() === 1,
                'even' => true,
                'odd' => false,
            ]);
        }

        return $this->index;
    }

    /**
     * @inheritDoc
     */
    public function setIndex(Index|array $index): self
    {
        $attributes = $index instanceof Index ? $index->toArray() : $index;
        if ($this->index instanceof Index) {
            $this->index->fill($attributes);
        } else {
            $this->index = new Index($attributes);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function increment(int $count = 1): self
    {
        for ($i = $count; $i > 0; $i--) {
            $this->index->iteration += 1;
            $this->index->index += 1;
            $this->index->remaining -= 1;
            $this->index->first = $this->index->index === 0;
            $this->index->last = $this->index->iteration === $this->index->count;
            $this->index->even = $this->index->index % 2 == 0;
            $this->index->odd = ! $this->index->even;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->results();
    }

    /**
     * @inheritDoc
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
