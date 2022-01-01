<?php

namespace IsaEken\Loops\Contracts;

use Closure;
use Stringable;

interface Workable extends Breakable, Arrayable, Jsonable, Stringable
{
    /**
     * Work the worker.
     *
     * @param LoopCallback|Closure|null $callback
     * @return void
     */
    public function work(LoopCallback|Closure|null $callback = null): void;

    /**
     * Get results from worker.
     *
     * @return array
     */
    public function results(): array;
}
