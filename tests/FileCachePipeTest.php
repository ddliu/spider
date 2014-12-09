<?php
use ddliu\spider\Spider;
use ddliu\spider\Pipe\FileCachePipe;
use ddliu\filecache\FileCache;
class FileCachePipeTest extends PHPUnit_Framework_TestCase {
    protected function getCacheFolder() {
        return __DIR__ . '/cache/filecachepipe';
    }

    public function setup() {
        $cache = new FileCache([
            'root' => $this->getCacheFolder(),
        ]);

        $cache->clear();
    }

    public function testIt() {
        $counter = 0;
        $data = [];
        $pipe = function($spider, $task) use (&$counter) {
            $counter = $counter + 1;
            $task['content'] = $counter;
        };

        $cachedPipe = new FileCachePipe($pipe, [
            'input' => 'url',
            'output' => 'content',
            'root' => $this->getCacheFolder(),
        ]);

        (new Spider())
            ->pipe($cachedPipe)
            ->pipe(function($spider, $task) use (&$data) {
                $data[] = $task['content'];
            })
            ->addTask('http://example.com/')
            ->addTask('http://example.com/')
            ->addTask('http://example.com/')
        ->run();

        $this->assertEquals(1, $counter);
        $this->assertEquals([1, 1, 1], $data);
    }
}