<?php
use ddliu\spider\Spider;
use ddliu\spider\Pipe\IfPipe;
class IfPipeTest extends PHPUnit_Framework_TestCase {

    private function shouldBe($condition, $rst) {
        $rst = $rst ? ['if']: ['else'];
        $data = [];
        (new Spider())
            ->pipe(new IfPipe(
                $condition, 
                function($spider, $task) use (&$data) {
                    $data[] = 'if';
                }, 
                function($spider, $task) use (&$data) {
                    $data[] = 'else';
                }
            ))
            ->addTask('')
            ->run();

        $this->assertEquals($rst, $data);
    }

    public function testIfBool() {
        $this->shouldBe(true, true);
    }


    public function testElseBool() {
        $this->shouldBe(false, false);
    }

    public function testIfCallback() {
        $this->shouldBe(function() {
            return true;
        }, true);
    }

    public function testElseCallback() {
        $this->shouldBe(function() {
            return false;
        }, false);
    }
}