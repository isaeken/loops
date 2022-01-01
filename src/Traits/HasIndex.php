<?php

namespace IsaEken\Loops\Traits;

use IsaEken\Loops\Index;

trait HasIndex
{
    /**
     * The index model of current loop.
     *
     * @var Index|null
     */
    private Index|null $index = null;

    /**
     * @inheritDoc
     */
    public function getIndex(): Index
    {
        if ($this->index === null) {
            $this->index = new Index([
                'iteration' => 1,
                'index' => 0,
                'remaining' => $this->getLength() - 1 ?? 0,
                'count' => $this->getLength(),
                'first' => true,
                'last' => $this->getLength() === 1,
                'even' => true,
                'odd' => false,
            ]);
        }

        return $this->index;
    }

    /**
     * @inheritDoc
     */
    public function setIndex(Index|array $index): void
    {
        $attributes = $index instanceof Index ? $index->toArray() : $index;
        if ($this->index instanceof Index) {
            $this->index->fill($attributes);
        } else {
            $this->index = new Index($attributes);
        }
    }

    /**
     * @inheritDoc
     */
    public function increment(int $count = 1): void
    {
        for ($i = $count; $i > 0; $i--) {
            $this->index->iteration += 1;
            $this->index->index += 1;
            $this->index->remaining -= 1;
            $this->index->first = $this->index->index === 0;
            $this->index->last = $this->index->iteration === $this->index->count;
            $this->index->even = $this->index->index % 2 == 0;
            $this->index->odd = ! $this->index->even;
        }
    }
}
