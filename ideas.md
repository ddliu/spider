## Usage

### Basic

```php
(new Spider([
    'depth' => 10,
]))
    ->pipe($pipe1)
    ->pipe($pipe2)
    ->pipe($pipe3)
    ->addTask($entryTaskData)
    ->run();
```

### Web Spider

```php
$spider
    ->pipe($webSpiderPipe)
    ->pipe($requeryPipe)
    ->pipe(function($spider, $task) {
        $task->content->findAll('#<a href="(.*)">.*</a>#Uis')->each(function($context) use ($task) {
            $task->fork([
                'url' => (string)$context[1]
            ]);
        });
    })
    ->addTask([
        'url' => 'http://example.com/'
    ])
    ->run();

```

### Special Task

```php

```