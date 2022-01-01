<?php

namespace IsaEken\Loops\Workers;

use Closure;
use IsaEken\Loops\Contracts\LoopCallback;
use IsaEken\Loops\Contracts\Workable;
use IsaEken\Loops\Exceptions\NotWorkedException;

class DefaultWorker implements Workable
{
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
     * @inheritDoc
     */
    public function work(Closure|LoopCallback|null $callback = null): void
    {
        $this->worked = false;
        $this->results = [];
    }

    /**
     * @inheritDoc
     */
    public function results(): array
    {
        if (!$this->worked) {
            throw new NotWorkedException;
        }

        return $this->results;
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
