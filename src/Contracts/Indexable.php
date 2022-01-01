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
     * @return void
     */
    public function setIndex(Index|array $index): void;

    /**
     * Increment the loop.
     *
     * @param int $count
     * @return void
     */
    public function increment(int $count = 1): void;
}
