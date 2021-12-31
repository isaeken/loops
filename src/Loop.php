<?php

namespace IsaEken\Loops;

use Closure;

class Loop
{
    private Index $index;

    /**
     * @var bool
     */
    private bool $run = true;

    /**
     * @param int $length
     * @param Closure $callback
     */
    public function __construct(public int $length, public Closure $callback)
    {
        $this->index = new Index([
            'iteration' => 1,
            'index' => 0,
            'remaining' => $this->length - 1 ?? 0,
            'count' => $this->length,
            'first' => true,
            'last' => $this->length === 1,
            'even' => true,
            'odd' => false,
        ]);
    }

    /**
     * Increment the loop.
     */
    public function increment(): void
    {
        $this->index->iteration += 1;
        $this->index->index += 1;
        $this->index->remaining -= 1;
        $this->index->first = $this->index->index === 0;
        $this->index->last = $this->index->iteration === $this->index->count;
        $this->index->even = $this->index->index % 2 == 0;
        $this->index->odd = !$this->index->even;
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
            $returns[] = $this->callback->call($this, clone $this->index, $this);
            $this->increment();

            if (!$this->run) {
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
