<?php
use ddliu\spider\Spider;
use ddliu\spider\Pipe\IconvPipe;

class IconvPipeTest extends PHPUnit_Framework_TestCase {
    public function getEncodedText($encoding = null) {
        $text = '你好，世界！';

        if ($encoding === null) {
            return $text;
        }

        return iconv('UTF-8', $encoding, $text);
    }

    public function doTest($tag) {
        $test = $this;
        (new Spider())
            ->pipe(function($spider, $task) use ($test, $tag) {
                $task['content'] = $tag.$test->getEncodedText('GBK');
                echo $task['content'].PHP_EOL;
            })
            ->pipe(new IconvPipe())
            ->pipe(function($spider, $task) use ($test, $tag) {
                echo $task['content'].PHP_EOL;
                $test->assertEquals($tag.$test->getEncodedText(), $task['content']);
            })
            ->addTask('test')
            ->run();
    }

    public function testIConv() {
        $this->doTest('<meta charset="gbk" />');
        $this->doTest('<meta http-equiv="Content-Type" content="text/html; charset=gbk" />');
    }
}