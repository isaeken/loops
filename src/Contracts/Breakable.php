<?php

namespace IsaEken\Loops\Contracts;

interface Breakable
{
    /**
     * Break worker after current execution.
     *
     * @return self
     */
    public function break(): self;
}
