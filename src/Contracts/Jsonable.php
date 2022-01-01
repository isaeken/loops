<?php

namespace IsaEken\Loops\Contracts;

interface Jsonable
{
    /**
     * Get the instance as a json.
     *
     * @return string
     */
    public function toJson(): string;
}
