<?php

use IsaEken\Loops\Loop;

if (! function_exists('loop')) {
    /**
     * Run the loop instance.
     *
     * @param  int  $length
     * @param  Closure  $callback
     *
     * @return array
     */
    function loop(int $length, Closure $callback): array
    {
        return (new Loop($length, $callback))->run();
    }
}

if (! function_exists('loop_random')) {
    /**
     * Call the loop function random times.
     *
     * @param  Closure  $callback
     * @param  int|null  $min
     * @param  int|null  $max
     *
     * @return array
     */
    function loop_random(Closure $callback, int|null $min = null, int|null $max = null): array
    {
        if ($min === null && $max === null) {
            $min = 0;
            $max = rand(0, 99);
        } elseif ($min === null) {
            $min = 0;
        } elseif ($max === null) {
            $max = rand($min, $min + 99);
        }

        return loop(rand($min, $max), $callback);
    }
}
