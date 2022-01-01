<?php

namespace IsaEken\Loops\Contracts;

interface Breakable
{
    /**
     * Break the worker.
     *
     * @return void
     */
    public function break(): void;
}
