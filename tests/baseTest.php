<?php
use ddliu\spider\Spider;
use ddliu\spider\Pipe\EchoPipe;
use ddliu\spider\Pipe\NormalizeUrlPipe;
use ddliu\spider\Pipe\RequestPipe;
use ddliu\spider\Pipe\RequeryPipe;
use ddliu\spider\Pipe\IfPipe;
use ddliu\spider\Pipe\IgnorePipe;

class BaseTest extends PHPUnit_Framework_TestCase {
    public function testPipe() {
        $test = $this;
        $spider = new Spider();
        $spider
            ->pipe(new EchoPipe("pipe1\n"))
            ->pipe(new EchoPipe("pipe2\n"))
            ->pipe(function($spider, $task) {
                $task['counter'] = 100;
            })
            ->pipe(function($spider, $task) {
                $task['counter'] = $task['counter'] + 1;
            })
            ->pipe(function($spider, $task) use ($test) {
                $test->assertEquals(101, $task['counter']);
            })
            ->addTask(array())
        ->run();
    }

    public function testNormalizeUrlPipe() {
        $test = $this;
        $spider = new Spider();
        $counter = 0;
        $spider
            ->pipe(new NormalizeUrlPipe())
            ->pipe(function($spider, $task) use (&$counter, $test) {
                if ($counter == 0) {
                    $test->assertEquals($task['url'], 'http://example.com/a/b.html');

                    $task->fork([
                        'url'=> 'c.html'
                    ]);
                } elseif ($counter == 1) {
                    $test->assertEquals($task['url'], 'http://example.com/a/c.html');
                }
                $counter++;
            })
            ->pipe(function($spider, $task) {
                echo $task['url'].PHP_EOL;
            })
            ->addTask([
                'url' => 'http://example.com/a/b.html',
            ])
        ->run();
    }

    public function testRequestPipe() {
        $test = $this;
        (new Spider())
            ->pipe(new RequestPipe([
                'useragent' => 'my spider',
            ]))
            ->pipe(function($spider, $task) {
                echo $task['content'].PHP_EOL;
            })
            ->pipe(function($spider, $task) use ($test) {
                $result = json_decode($task['content'], true);
                $test->assertEquals('my spider', $result['user-agent']);
            })
            ->addTask([
                'url' => 'http://httpbin.org/user-agent',
            ])
        ->run()
        ->report();
    }

    public function testRequeryPipe() {
        $test = $this;
        (new Spider())
            ->pipe(new RequestPipe())
            ->pipe(new RequeryPipe())
            ->pipe(function($spider, $task) use ($test) {
                $test->assertEquals('Example Domain', $task['$requery']->find('#<title>(.*)</title>#Uis')->extract(1));
            })
            ->addTask([
                'url' => 'http://example.com/',
            ])
        ->run();
    }

    public function testIgnorePipe() {
        $test = $this;
        (new Spider())
            ->pipe(new IgnorePipe())
            ->pipe(function($spider, $task) use ($test) {
                $this->assertTrue(false, 'should not come here');
            })
            ->addTask([
            ])
        ->run();
    }

    public function testIfPipe() {
        $test = $this;
        (new Spider())
            ->pipe(new IfPipe(function($spider, $task) {
                return true;
            }, function($spider, $task) use ($test) {
                $this->assertTrue(true);
            }, function($spider, $task) use ($test) {
                $this->assertTrue(false, 'should not come here!');
            }))
            ->addTask([])
        ->run();
    }

    public function testLimit() {
        $counter = 0;
        (new Spider([
            'limit' => 999
        ]))
            ->pipe(function($spider, $task) use (&$counter) {
                for ($i = 0; $i < 10; $i++) {
                    $task->fork([]);
                }
                $counter++;
            })
            ->addTask([
            ])
        ->run()
        ->report();

        $this->assertEquals(999, $counter);
    }
}