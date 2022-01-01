<?php

namespace IsaEken\Loops\Contracts;

use Closure;

interface Workable extends HasLooper, Indexable
{
    /**
     * Work the worker.
     *
     * @param LoopCallback|Closure|null $callback
     * @return self
     */
    public function work(LoopCallback|Closure|null $callback = null): self;

    /**
     * Get results from worker.
     *
     * @return array
     */
    public function results(): array;
}
