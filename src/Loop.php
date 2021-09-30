<?php

namespace IsaEken\Loops;

use Closure;

class Loop
{
    /**
     * @var object
     */
    private object $loop;

    /**
     * @var bool
     */
    private bool $run = true;

    /**
     * @param  int  $length
     * @param  Closure  $callback
     */
    public function __construct(
        public int $length,
        public Closure $callback,
    ) {
        $this->loop = (object) [
            'iteration' => 0,
            'index' => 0,
            'remaining' => $this->length - 1 ?? null,
            'count' => $this->length,
            'first' => true,
            'last' => $this->length == 1,
            'odd' => false,
            'even' => true,
        ];
    }

    /**
     * Increment the loop.
     */
    public function increment(): void
    {
        $this->loop->iteration = $this->loop->iteration + 1;
        $this->loop->index = $this->loop->iteration;
        $this->loop->first = $this->loop->iteration == 0;
        $this->loop->last = $this->loop->iteration == $this->loop->count - 1;
        $this->loop->odd = ! $this->loop->odd;
        $this->loop->even = ! $this->loop->even;
        $this->loop->remaining = $this->loop->remaining - 1;
    }

    /**
     * Run the loop.
     *
     * @return array
     */
    public function run(): array
    {
        $returns = [];
        for ($index = 0; $index < $this->length; $index++) {
            $returns[] = $this->callback->call($this, clone $this->loop, $this);
            $this->increment();

            if (! $this->run) {
                break;
            }
        }

        return $returns;
    }

    /**
     * Break loop.
     */
    public function stop(): void
    {
        $this->run = false;
    }
}
