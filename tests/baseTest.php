<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

use ddliu\spider\Spider;
use ddliu\spider\Pipe\EchoPipe;
use ddliu\spider\Pipe\NormalizeUrlPipe;
use ddliu\spider\Pipe\RequestPipe;
use ddliu\spider\Pipe\RequeryPipe;
use ddliu\spider\Pipe\DomCrawlerPipe;
use ddliu\spider\Pipe\IfUrlPipe;
use ddliu\spider\Pipe\IgnorePipe;
use ddliu\spider\Pipe\ReportPipe;
use ddliu\spider\Pipe\FileCachePipe;
use ddliu\spider\Pipe\RetryPipe;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class BaseTest extends PHPUnit_Framework_TestCase {
    private function newSpider($options = null) {
        $spider = new Spider($options);
        $spider->logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

        return $spider;
    }

    public function testPipe() {
        $test = $this;
        $this->newSpider()
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
        $counter = 0;
        $this->newSpider()
            ->pipe(new NormalizeUrlPipe())
            ->pipe(function($spider, $task) use (&$counter, $test) {
                if ($counter == 0) {
                    $test->assertEquals($task['url'], 'http://example.com/a/b.html');

                    $task->fork('c.html');
                } elseif ($counter == 1) {
                    $test->assertEquals($task['url'], 'http://example.com/a/c.html');
                }
                $counter++;
            })
            ->pipe(function($spider, $task) {
                echo $task['url'].PHP_EOL;
            })
            ->addTask('http://example.com/a/b.html')
        ->run();
    }

    public function testRequestPipe() {
        $test = $this;
        $this->newSpider()
            ->pipe(new RequestPipe([
                'timeout' => 10,
                'useragent' => 'my spider',
            ]))
            ->pipe(function($spider, $task) {
                echo $task['content'].PHP_EOL;
            })
            ->pipe(function($spider, $task) use ($test) {
                $result = json_decode($task['content'], true);
                $test->assertEquals('my spider', $result['user-agent']);
            })
            ->addTask('http://httpbin.org/user-agent')
        ->run()
        ->report();
    }

    public function testFileCachePipe() {
        $reqPipe = new RequestPipe([
            'timeout' => 10,
            'useragent' => 'my spider',
        ]);

        $cacheForReq = new FileCachePipe($reqPipe, [
            'input' => 'url',
            'output' => 'content',
            'root' => __DIR__ . '/cache'
        ]);

        $this->newSpider()
            ->pipe($cacheForReq)
            ->pipe(function($spider, $task) {
                // TODO: add assertions
            })
            ->addTask('http://example.com/')
            ->addTask('http://example.com/')
            ->addTask('http://example.com/')
        ->run();
    }

    public function testRetryPipe() {
        $counter = 0;

        $this->newSpider()
            ->pipe(new RetryPipe(function($spider, $task) use (&$counter) {
                if ($counter < 10) {
                    throw new \Exception("kill it");
                }
            }, array('count' => 13)))
            ->addTask('test')
        ->run();
    }

    public function testRequeryPipe() {
        $test = $this;
        $this->newSpider()
            ->pipe(new RequestPipe())
            ->pipe(new RequeryPipe())
            ->pipe(function($spider, $task) use ($test) {
                $test->assertEquals('Example Domain', $task['$requery']->find('#<title>(.*)</title>#Uis')->extract(1));
            })
            ->addTask('http://example.com/')
        ->run();
    }

    public function testDomCrawlerPipe() {
        $test = $this;
        $this->newSpider()
            ->pipe(new RequestPipe())
            ->pipe(new DomCrawlerPipe())
            ->pipe(function($spider, $task) use ($test) {
                $title = $task['$dom']->filter('title')->text();
                $test->assertEquals('Example Domain', $title);
            })
            ->addTask('http://example.com/')
        ->run();
    }

    public function testIgnorePipe() {
        $test = $this;
        $this->newSpider()
            ->pipe(new IgnorePipe())
            ->pipe(function($spider, $task) use ($test) {
                $this->assertTrue(false, 'should not come here');
            })
            ->addTask([
            ])
        ->run();
    }

    public function testIfUrlPipe() {
        $this->newSpider()
            ->pipe(new IfUrlPipe('/abc/', function($spider, $task) {
                $spider->logger->addInfo('IfUrlPipe passed');
                $this->assertTrue(true);
            }, function($spider, $task) {
                $this->assertTrue(false, 'should not come here!');
            }))
            ->addTask('cbabcdef')
        ->run();
    }

    public function testReportPipeCount() {
        $spider = $this->newSpider()
            ->pipe(new ReportPipe([
                'count' => 2
            ]));

        for ($i = 0; $i < 10; $i++) {
            $spider->addTask([]);
        }

        $spider->run()->report();
    }

    public function testReportPipeSeconds() {
        $spider = $this->newSpider()
            ->pipe(new ReportPipe([
                'seconds' => 2
            ]))
            ->pipe(function($spider, $task) {
                sleep(1);
            });

        for ($i = 0; $i < 5; $i++) {
            $spider->addTask([]);
        }

        $spider->run()->report();
    }    

    public function testLimit() {
        $counter = 0;
        $this->newSpider([
            'limit' => 999
        ])
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