<?php

namespace IsaEken\Loops\Contracts;

use IsaEken\Loops\Index;
use IsaEken\Loops\Loop;

interface LoopCallback
{
    public function __invoke(Index $index, Loop $loop = null): mixed;
}
