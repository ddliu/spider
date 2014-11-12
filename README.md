# Spider

A flexible spider in PHP.

## Concepts

A spider contains many processors called `pipes`, you can pass as many tasks as you like to the spider, each task go through these `pipes` and get processed.

## Installation

```
composer require ddliu/spider
```

## Usage

```php
use ddliu\spider\Spider;

(new Spider())
    // a simple request pipe
    ->pipe(function($spider, $task) {
        $task['content'] = file_get_contents($task['url']);
    })
    ->pipe(function($spider, $task) {
        // extract all links
        // ...
        foreach ($links as $link) {
            // start a new task
            $task->fork(array(
                'url' => $link['url']
            ));
        }
    })
    // the entry task
    ->addTask(array(
        'url' => 'http://example.com',
    ))
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

### RequestPipe

Start an HTTP request with `$task['url']` and save the result in `$task['content']`.

```php
new RequestPipe(array(
    'useragent' => 'myspider',
    'timeout' => 10
));
```

### NormalizeUrlPipe

Normalize `$task['url']`.

```php
new NormalizeUrlPipe()
```

### ReportPipe

Report every 10 minutes.

```php
new ReportPipe(array(
    'seconds' => 600
))
```