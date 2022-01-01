<?php

namespace IsaEken\Loops;

use Closure;
use IsaEken\Loops\Contracts\Arrayable;
use IsaEken\Loops\Contracts\Jsonable;
use IsaEken\Loops\Contracts\LoopCallback;
use IsaEken\Loops\Contracts\Looper;
use IsaEken\Loops\Contracts\Workable;
use IsaEken\Loops\Workers\DefaultWorker;
use Opis\Closure\SerializableClosure;
use Stringable;

class Loop implements Looper, Arrayable, Jsonable, Stringable
{
    /**
     * The Worker instance.
     *
     * @var Workable|null
     */
    private Workable|null $worker = null;

    /**
     * @var int
     */
    private int $length = 0;

    /**
     * @var LoopCallback|Closure|SerializableClosure|null
     */
    private LoopCallback|Closure|SerializableClosure|null $callback = null;

    /**
     * @param int $length Count of loop indexes.
     * @param LoopCallback|Closure|SerializableClosure|null $callback Closure to be called every loop.
     */
    public function __construct(int $length, LoopCallback|Closure|SerializableClosure|null $callback = null)
    {
        $this->setLength($length);
        $this->setCallback($callback);

        if ($this->worker === null) {
            $this->setWorker(new DefaultWorker());
        }
    }

    /**
     * @inheritDoc
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @inheritDoc
     */
    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCallback(): LoopCallback|Closure|SerializableClosure|null
    {
        return $this->callback;
    }

    /**
     * @inheritDoc
     */
    public function setCallback(Closure|LoopCallback|SerializableClosure|null $callback = null): self
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getWorker(): Workable
    {
        return $this->worker;
    }

    /**
     * @inheritDoc
     */
    public function setWorker(Workable|string $worker): self
    {
        if ($worker instanceof Workable) {
            $this->worker = $worker;
        } else {
            $this->worker = new $worker();
        }

        $this->worker->setLooper($this);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function run(): self
    {
        $this->worker->work($this->callback);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function results(): array
    {
        return $this->worker->results();
    }

    /**
     * @inheritDoc
     */
    public function break(): self
    {
        $this->worker->break();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->worker->toArray();
    }

    /**
     * @inheritDoc
     */
    public function toJson(): string
    {
        return $this->worker->toJson();
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->worker->__toString();
    }
}
