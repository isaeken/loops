# Loops

[![Latest Version on Packagist](https://img.shields.io/packagist/v/isaeken/loops.svg?style=flat-square)](https://packagist.org/packages/isaeken/loops)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/isaeken/loops/run-tests?label=tests)](https://github.com/isaeken/loops/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/isaeken/loops/Check%20&%20fix%20styling?label=code%20style)](https://github.com/isaeken/loops/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/isaeken/loops.svg?style=flat-square)](https://packagist.org/packages/isaeken/loops)

## Installation

You can install the package via composer:

```bash
composer require isaeken/loops
```

## Usage

### Basic Usage

````php
loop(5, function (\IsaEken\Loops\Index $index) {
    return $index->odd;
}); // [false, true, false, true, false]
````

### Using with class method

```php
$callback = new class implements \IsaEken\Loops\Contracts\LoopCallback {
    public function __invoke(Index $index, Loop $loop = null): int
    {
        return $index->index;
    }
};

$loop = new Loop(2, $callback);
$loop->run(); // [0, 1]
```

### Get current loop

````php
loop(2 ,function (\IsaEken\Loops\Index $index, \IsaEken\Loops\Loop $loop) {
    return [
        'iteration' => $index->iteration,
        'index'     => $index->index,
        'remaining' => $index->remaining,
        'count'     => $index->count,
        'first'     => $index->first,
        'last'      => $index->last,
        'odd'       => $index->odd,
        'even'      => $index->even,
    ];
});
// [
//  [
//    'iteration' => 1,
//    'index' => 0,
//    'remaining' => 1,
//    'count' => 2,
//    'first' => true,
//    'last' => false,
//    'odd' => false,
//    'even' => true,
//  ],
//  [
//    'iteration' => 2,
//    'index' => 1,
//    'remaining' => 0,
//    'count' => 2,
//    'first' => false,
//    'last' => true,
//    'odd' => true,
//    'even' => false,
//  ]
// ]
````

### Break the loop

````php
loop(3, function (\IsaEken\Loops\Index $index, \IsaEken\Loops\Loop $loop) {
    if ($index->index > 1) {
        $loop->break();
    }
    
    return $index->index;
}); // [0, 1]
````

### Loop random times

````php
loop_random(function (\IsaEken\Loops\Index $index, \IsaEken\Loops\Loop $loop) {
    return $index->index;
}); // executed random times.

$min = 5;
$max = 10;

loop_random(function (\IsaEken\Loops\Index $index, \IsaEken\Loops\Loop $loop) {
    return $index->index;
}, $min, $max);
````

### Loop random with seed

```php
loop_random(function (\IsaEken\Loops\Index $index) {
    return $index->even;
}, seed: 123456789);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Isa Eken](https://github.com/isaeken)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
