<?php

namespace IsaEken\Loops\Contracts;

use IsaEken\Loops\Index;

interface Loopable
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
     * Get the loop length.
     *
     * @return int
     */
    public function getLength(): int;

    /**
     * Set the loop length.
     *
     * @param int $length
     * @return void
     */
    public function setLength(int $length): void;

    /**
     * Increment the loop.
     *
     * @param int $count
     * @return void
     */
    public function increment(int $count = 1): void;
}
