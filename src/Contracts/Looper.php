<?php

namespace IsaEken\Loops\Contracts;

use Closure;

interface Looper
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
     * @return void
     */
    public function setLength(int $length): void;

    /**
     * Set loop callback.
     *
     * @param LoopCallback|Closure|null $callback
     * @return void
     */
    public function setCallback(LoopCallback|Closure|null $callback = null): void;

    /**
     * Get loop callback.
     *
     * @return LoopCallback|Closure|null
     */
    public function getCallback(): LoopCallback|Closure|null;

    /**
     * Set worker instance.
     *
     * @param Workable $worker
     * @return void
     */
    public function setWorker(Workable $worker): void;

    /**
     * Get worker instance.
     *
     * @return Workable
     */
    public function getWorker(): Workable;

    /**
     * Run the loop.
     *
     * @return void
     */
    public function run(): void;

    /**
     * Get results of the executed loop.
     *
     * @return array
     */
    public function results(): array;

    /**
     * Break loop after current worker loop.
     *
     * @return void
     */
    public function break(): void;
}
