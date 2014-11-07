<?php
namespace ddliu\spider\Pipe;

class IfUrlPipe extends BasePipe {
    protected $realPipe;
    public function __construct($condition, $pipe, $elsePipe) {
        $this->realPipe = new IfPipe(function($spider, $task) use ($condition) {
            return preg_match($condition, $task['url']);
        }, $pipe, $elsePipe);
    }

    public function run($spider, $task) {
        $this->realPipe->run($spider, $task);
    }
}