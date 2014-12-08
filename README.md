# Spider [![Build Status](https://travis-ci.org/ddliu/spider.svg)](https://travis-ci.org/ddliu/spider)

A flexible spider in PHP.

## Concepts

A spider contains many processors called `pipes`, you can pass as many tasks as you like to the spider, each task go through these `pipes` and get processed.

## Installation

```
composer require ddliu/spider
```

## Requirements

- PHP5.3+
- curl(RequestPipe)

## Dependencies

See `composer.json`.

## Usage

```php
use ddliu\spider\Spider;
use ddliu\spider\Pipe\NormalizeUrlPipe;
use ddliu\spider\Pipe\RequestPipe;
use ddliu\spider\Pipe\DomCrawlerPipe;

(new Spider())
    ->pipe(new NormalizeUrlPipe())
    ->pipe(new RequestPipe())
    ->pipe(new DomCrawlerPipe())
    ->pipe(function($spider, $task) {
        $task['$dom']->filter('a')->each(function($a) use ($task) {
            $href = $a->attr('href');
            $task->fork($href);
        })
    })
    // the entry task
    ->addTask('http://example.com')
    ->run()
    ->report();
```

Find more examples in `examples` folder.

## Spider

The `Spider` class.

### Options

- limit: maxmum tasks to run

### Methods

- `pipe($pipe)`: add a pipe
- `addTask($task)`: add a task
- `run()`: run the spider
- `report()`: write report to log

## Task

A task contains the data array and some helper functions.

The `Task` class implements `ArrayAccess` interface, so you can access data like array.

### Methods

- `fork($task)`: add a sub task to the spider
- `ignore()`: ignore the task


## Pipes

Pipes define how each task being processed.

A pipe can be a function:

```php
function($spider, $task) {}
```

Or extends the BasePipe:

```php
use ddliu\spider\Pipe\BasePipe;

class MyPipe extends BasePipe {
    public function run($spider, $task) {
        // process the task...
    }
}
```

## Useful Pipes

### NormalizeUrlPipe

Normalize `$task['url']`.

```php
new NormalizeUrlPipe()
```

### RequestPipe

Start an HTTP request with `$task['url']` and save the result in `$task['content']`.

```php
new RequestPipe(array(
    'useragent' => 'myspider',
    'timeout' => 10
));
```

### FileCachePipe

Cache a pipe (e.g. `RequestPipe`).

```php
$requestPipe = new RequestPipe();
$cacheForReqPipe = new FileCachePipe($requestPipe, [
    'input' => 'url',
    'output' => 'content',
    'root' => '/path/to/cache/root',
]);
```

### RetryPipe

Retry on failure.

```php
$requestPipe = new RequestPipe();
$retryForReqPipe = new RetryPipe($requestPipe, [
    'count' => 10,
]);
```

### DomCrawlerPipe

Create a [DomCrawler](https://github.com/symfony/DomCrawler) from `$task['content']`. Access it with `$task['$dom']` in following pipes.

### ReportPipe

Report every 10 minutes.

```php
new ReportPipe(array(
    'seconds' => 600
))
```

## TODO/Ideas

- Real world examples.
- Running tasks concurrently.(With pthread?)
