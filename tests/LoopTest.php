<?php

use IsaEken\Loops\Contracts\LoopCallback;
use IsaEken\Loops\Index;
use IsaEken\Loops\Loop;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThanOrEqual;
use function PHPUnit\Framework\assertLessThanOrEqual;
use function PHPUnit\Framework\assertTrue;

it('loop is working', function () {
    $loop = new Loop(2, function (Index $index) {
        return $index->index;
    });

    $loop->run();

    assertCount(2, $loop->results());
});

it('loop is breakable', function () {
    $loop = new Loop(2, function (Index $index, Loop $loop) {
        $loop->break();
    });

    $loop->run();

    assertCount(1, $loop->results());

    $loop = new Loop(2);
    $loop->setCallback(function (Index $index) use ($loop) {
        $loop->break();
    });
    $loop->run();

    assertCount(1, $loop->results());
});

it('loop incrementing is valid', function () {
    $loop = new Loop(3, function (Index $index) {
        return $index;
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

    $loop->run();

    assertEquals($returns, $loop->results());
});

it('run helpers', function () {
    assertCount(2, loop(2, fn () => ''));
    assertGreaterThanOrEqual(0, count(loop_random(fn () => '')));
});

it('random is correct', function () {
    assertGreaterThanOrEqual(10, count(loop_random(fn () => '', 10)));
    assertLessThanOrEqual(10, count(loop_random(fn () => '', null, 10)));

    $count = count(loop_random(fn () => '', 5, 10));
    assertTrue($count >= 5 && $count <= 10);

    $seed = 123456789;
    assertEquals(4, count(loop_random(fn () => '', seed: $seed)));
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

it('loop callback is working', function () {
    $callback = new class () implements LoopCallback {
        public function __invoke(Index $index, Loop $loop = null): int
        {
            return $index->index;
        }
    };

    $loop = new Loop(2, $callback);
    $loop->run();
    assertEquals([0, 1], $loop->results());
});

it('can be convert to array, json or string', function () {
    $loop = loop(2, function (Index $index) {
        return $index->toArray();
    });

    assertEquals([
        ["iteration" => 1, "index" => 0, "remaining" => 1, "count" => 2, "first" => true, "last" => false, "even" => true, "odd" => false],
        ["iteration" => 2, "index" => 1, "remaining" => 0, "count" => 2, "first" => false, "last" => true, "even" => false, "odd" => true],
    ], $loop);

    $loop = loop(2, function (Index $index) {
        return $index->toJson();
    });

    assertEquals([
        "{\"iteration\":1,\"index\":0,\"remaining\":1,\"count\":2,\"first\":true,\"last\":false,\"even\":true,\"odd\":false}",
        "{\"iteration\":2,\"index\":1,\"remaining\":0,\"count\":2,\"first\":false,\"last\":true,\"even\":false,\"odd\":true}",
    ], $loop);

    $loop = new Loop(2, fn (Index $index) => $index->index);
    $loop->run();
    assertEquals([0, 1], $loop->results());
    assertEquals([0, 1], $loop->toArray());
    assertEquals("[0,1]", $loop->toJson());
    assertEquals("[0,1]", $loop->__toString());
});

it('can async worker is running', function () {
    $array = loop(100, function (Index $index) {
        return $index->even;
    });

    $loop = async_loop(100, function (Index $index) {
        usleep(100000);

        return $index->even;
    });

    assertEquals($array, await($loop));
});

it('async worker is breakable', function () {
    $loop = async_loop(100, function (Index $index, Loop $loop) {
        if ($index->iteration == 50) {
            $loop->break();
        }

        return $index->iteration;
    });

    assertCount(50, await($loop));
});

it('random async worker is correct', function () {
    assertGreaterThanOrEqual(10, count(await(async_loop_random(fn () => '', 10))));
    assertLessThanOrEqual(10, count(await(async_loop_random(fn () => '', max: 10))));

    $count = count(await(async_loop_random(fn () => '', 5, 10)));
    assertTrue($count >= 5 && $count <= 10);

    $seed = 123456789;
    assertEquals(4, count(await(async_loop_random(fn () => '', seed: $seed))));
});
