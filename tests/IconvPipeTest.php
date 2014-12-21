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

    public function testIConv() {
        $test = $this;
        (new Spider())
            ->pipe(function($spider, $task) use ($test) {
                $task['content'] = '<meta charset="gbk" />'.$test->getEncodedText('GBK');
                echo $task['content'].PHP_EOL;
            })
            ->pipe(new IconvPipe())
            ->pipe(function($spider, $task) use ($test) {
                echo $task['content'].PHP_EOL;
                $test->assertEquals('<meta charset="gbk" />'.$test->getEncodedText(), $task['content']);
            })
            ->addTask('test')
            ->run();
    }
}