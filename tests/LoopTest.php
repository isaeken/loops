<?php

use IsaEken\Loops\Loop;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThanOrEqual;
use function PHPUnit\Framework\assertLessThanOrEqual;
use function PHPUnit\Framework\assertTrue;

it('loop is working', function () {
    $loop = new Loop(2, function ($loop) {
        return $loop->index;
    });

    assertCount(2, $loop->run());
});

it('loop is breakable', function () {
    $loop = new Loop(2, function ($loop, $instance) {
        $instance->stop();
    });

    assertCount(1, $loop->run());
});

it('loop incrementing is valid', function () {
    $loop = new Loop(2, function ($loop) {
        return $loop;
    });

    $return = [
        (object)[
            'iteration' => 0,
            'index' => 0,
            'remaining' => 1,
            'count' => 2,
            'first' => true,
            'last' => false,
            'odd' => false,
            'even' => true,
        ],
        (object)[
            'iteration' => 1,
            'index' => 1,
            'remaining' => 0,
            'count' => 2,
            'first' => false,
            'last' => true,
            'odd' => true,
            'even' => false,
        ],
    ];

    assertEquals($return, $loop->run());
});

it('run helpers', function () {
    assertCount(2, loop(2, fn() => ''));
    assertGreaterThanOrEqual(0, count(loop_random(fn() => '')));
});

it('random is correct', function () {
    assertGreaterThanOrEqual(10, count(loop_random(fn() => '', 10)));
    assertLessThanOrEqual(10, count(loop_random(fn() => '', null, 10)));

    $count = count(loop_random(fn() => '', 5, 10));
    assertTrue($count >= 5 && $count <= 10);
});
