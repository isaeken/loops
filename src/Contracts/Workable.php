<?php

namespace IsaEken\Loops\Contracts;

use Closure;
use Opis\Closure\SerializableClosure;

interface Workable extends HasLooper, Indexable
{
    /**
     * Work the worker.
     *
     * @param LoopCallback|Closure|SerializableClosure|null $callback
     * @return mixed
     */
    public function work(LoopCallback|Closure|SerializableClosure|null $callback = null): mixed;

    /**
     * Get results from worker.
     *
     * @return array
     */
    public function results(): array;
}
