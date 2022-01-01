<?php

namespace IsaEken\Loops\Contracts;

interface Breakable
{
    /**
     * Break worker after current execution.
     *
     * @return void
     */
    public function break(): void;
}
