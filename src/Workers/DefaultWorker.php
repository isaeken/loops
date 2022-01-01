<?php

namespace IsaEken\Loops\Workers;

use Closure;
use IsaEken\Loops\Contracts\LoopCallback;
use IsaEken\Loops\Contracts\Looper;
use IsaEken\Loops\Contracts\Workable;
use IsaEken\Loops\Exceptions\NotWorkedException;
use IsaEken\Loops\Index;

class DefaultWorker implements Workable
{
    /**
     * The Looper instance.
     *
     * @var Looper $looper
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

    private bool $breaked = false;

    /**
     * The index model of current loop.
     *
     * @var Index|null $index
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
    public function setLooper(Looper $looper): void
    {
        $this->looper = $looper;
    }

    /**
     * @inheritDoc
     */
    public function work(Closure|LoopCallback|null $callback = null): void
    {
        $this->worked = false;
        $this->results = [];

        for ($index = 0; $index < $this->getLooper()->getLength(); $index++) {
            if ($callback === null) {
                $this->results[] = null;
            } elseif ($callback instanceof LoopCallback) {
                $this->results[] = call_user_func($callback, clone $this->getIndex(), $this->getLooper());
            } else {
                $this->results[] = $callback->call($this->getLooper(), clone $this->getIndex(), $this->getLooper());
            }

            $this->increment();

            if ($this->breaked) {
                break;
            }
        }

        $this->worked = true;
    }

    /**
     * @inheritDoc
     */
    public function break(): void
    {
        $this->breaked = true;
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
    public function setIndex(Index|array $index): void
    {
        $attributes = $index instanceof Index ? $index->toArray() : $index;
        if ($this->index instanceof Index) {
            $this->index->fill($attributes);
        } else {
            $this->index = new Index($attributes);
        }
    }

    /**
     * @inheritDoc
     */
    public function increment(int $count = 1): void
    {
        for ($i = $count; $i > 0; $i--) {
            $this->index->iteration += 1;
            $this->index->index += 1;
            $this->index->remaining -= 1;
            $this->index->first = $this->index->index === 0;
            $this->index->last = $this->index->iteration === $this->index->count;
            $this->index->even = $this->index->index % 2 == 0;
            $this->index->odd = !$this->index->even;
        }
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
