<?php
use ddliu\spider\Spider;
use ddliu\spider\Pipe\IfUrlRegexpPipe;
class IfUrlRegexpPipeTest extends PHPUnit_Framework_TestCase {

    private function shouldBe($condition, $rst) {
        $rst = $rst ? ['if']: ['else'];
        $data = [];
        (new Spider())
            ->pipe(new IfUrlRegexpPipe(
                $condition, 
                function($spider, $task) use (&$data) {
                    $data[] = 'if';
                }, 
                function($spider, $task) use (&$data) {
                    $data[] = 'else';
                }
            ))
            ->addTask('http://google.com/search')
            ->run();

        $this->assertEquals($rst, $data);
    }

    public function testPass() {
        $this->shouldBe('#.*/search$#', true);
    }

    public function testFail() {
        $this->shouldBe('#facebook\.com/search#', false);
    }
}