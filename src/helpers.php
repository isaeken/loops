<?php

use IsaEken\Loops\Contracts\LoopCallback;
use IsaEken\Loops\Loop;
use IsaEken\Loops\Workers\AsyncWorker;
use Opis\Closure\SerializableClosure;

if (! function_exists('loop')) {
    /**
     * Run the loop instance.
     *
     * @param int $length
     * @param LoopCallback|Closure|SerializableClosure|null $callback
     *
     * @return array
     */
    function loop(int $length, LoopCallback|Closure|SerializableClosure|null $callback): array
    {
        $loop = new Loop($length, $callback);
        $loop->run();

        return $loop->results();
    }
}

if (! function_exists('loop_random')) {
    /**
     * Call the loop function random times.
     *
     * @param LoopCallback|Closure|SerializableClosure|null $callback
     * @param int|null $min
     * @param int|null $max
     * @param int|null $seed
     * @return array
     */
    function loop_random(LoopCallback|Closure|SerializableClosure|null $callback, int|null $min = null, int|null $max = null, int|null $seed = null): array
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

if (! function_exists('async_loop')) {
    function async_loop(int $length, LoopCallback|Closure|SerializableClosure|null $callback): \Spatie\Async\Pool
    {
        $loop = new Loop($length, $callback);
        $loop->setWorker(AsyncWorker::class);

        return $loop->getWorker()->work($loop->getCallback());
    }
}

if (! function_exists('async_loop_random')) {
    function async_loop_random(LoopCallback|Closure|SerializableClosure|null $callback, int|null $min = null, int|null $max = null, int|null $seed = null): \Spatie\Async\Pool
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

        return async_loop(rand($min, $max), $callback);
    }
}
