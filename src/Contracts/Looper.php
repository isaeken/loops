<?php

namespace IsaEken\Loops\Contracts;

use Closure;

interface Looper extends Breakable
{
    /**
     * Get count of loop indexes.
     *
     * @return int
     */
    public function getLength(): int;

    /**
     * Set loop count.
     *
     * @param int $length
     * @return self
     */
    public function setLength(int $length): self;

    /**
     * Set loop callback.
     *
     * @param LoopCallback|Closure|null $callback
     * @return self
     */
    public function setCallback(LoopCallback|Closure|null $callback = null): self;

    /**
     * Get loop callback.
     *
     * @return LoopCallback|Closure|null
     */
    public function getCallback(): LoopCallback|Closure|null;

    /**
     * Set worker instance.
     *
     * @param Workable|string $worker
     * @return self
     */
    public function setWorker(Workable|string $worker): self;

    /**
     * Get worker instance.
     *
     * @return Workable
     */
    public function getWorker(): Workable;

    /**
     * Run the loop.
     *
     * @return self
     */
    public function run(): self;

    /**
     * Get results of the executed loop.
     *
     * @return array
     */
    public function results(): array;
}
