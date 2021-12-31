<?php

use IsaEken\Loops\Index;
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
        $instance->break();
    });

    assertCount(1, $loop->run());
});

it('loop incrementing is valid', function () {
    $loop = new Loop(3, function ($loop) {
        return $loop;
    });

    $returns = [
        new Index([
            'iteration' => 1,
            'index' => 0,
            'remaining' => 2,
            'count' => 3,
            'first' => true,
            'last' => false,
            'even' => true,
            'odd' => false,
        ]),
        new Index([
            'iteration' => 2,
            'index' => 1,
            'remaining' => 1,
            'count' => 3,
            'first' => false,
            'last' => false,
            'even' => false,
            'odd' => true,
        ]),
        new Index([
            'iteration' => 3,
            'index' => 2,
            'remaining' => 0,
            'count' => 3,
            'first' => false,
            'last' => true,
            'even' => true,
            'odd' => false,
        ]),
    ];

    assertEquals($returns, $loop->run());
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

it('run index is correctly', function () {
    $index = new Index([
        'iteration' => 1,
    ]);
    assertEquals(1, $index->iteration);

    $index->remaining = 2;
    assertEquals(2, $index->getAttribute('remaining'));

    $index->setAttribute('first', true);
    assertEquals(true, $index->first);

    $index->fill(['odd' => true]);
    assertEquals(true, $index->odd);
});
