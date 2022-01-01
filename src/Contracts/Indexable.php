<?php

namespace IsaEken\Loops\Contracts;

use IsaEken\Loops\Index;

interface Indexable
{
    /**
     * Get the index.
     *
     * @return Index
     */
    public function getIndex(): Index;

    /**
     * Set the index.
     *
     * @param Index|array $index
     * @return self
     */
    public function setIndex(Index|array $index): self;

    /**
     * Increment the loop.
     *
     * @param int $count
     * @return self
     */
    public function increment(int $count = 1): self;
}
