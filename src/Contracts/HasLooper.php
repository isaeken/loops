<?php

namespace IsaEken\Loops\Contracts;

interface HasLooper
{
    /**
     * Get the looper instance.
     *
     * @return Looper
     */
    public function getLooper(): Looper;

    /**
     * Set the looper instance.
     *
     * @param Looper $looper
     * @return void
     */
    public function setLooper(Looper $looper): void;
}
