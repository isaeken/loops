<?php

use IsaEken\Loops\Contracts\LoopCallback;
use IsaEken\Loops\Loop;

if (! function_exists('loop')) {
    /**
     * Run the loop instance.
     *
     * @param int $length
     * @param LoopCallback|Closure $callback
     *
     * @return array
     */
    function loop(int $length, LoopCallback|Closure $callback): array
    {
        return (new Loop($length, $callback))->run();
    }
}

if (! function_exists('loop_random')) {
    /**
     * Call the loop function random times.
     *
     * @param LoopCallback|Closure $callback
     * @param int|null $min
     * @param int|null $max
     * @param int|null $seed
     * @return array
     */
    function loop_random(LoopCallback|Closure $callback, int|null $min = null, int|null $max = null, int|null $seed = null): array
    {
        if ($seed === null) {
            $seed = time();
        }

        srand($seed);

        if ($min === null) {
            $min = 0;
        }

        if ($max === null) {
            $max = rand($min, $min + 99);
        }

        return loop(rand($min, $max), $callback);
    }
}
