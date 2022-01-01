<?php

namespace IsaEken\Loops\Contracts;

use Closure;

interface Workable extends HasLooper, Indexable
{
    /**
     * Work the worker.
     *
     * @param LoopCallback|Closure|null $callback
     * @return mixed
     */
    public function work(LoopCallback|Closure|null $callback = null): mixed;

    /**
     * Get results from worker.
     *
     * @return array
     */
    public function results(): array;
}
