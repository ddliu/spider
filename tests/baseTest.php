<?php
use ddliu\spider\Spider;
use ddliu\spider\Pipe\EchoPipe;
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
}