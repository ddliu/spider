<?php
namespace ddliu\spider\Pipe;

class FunctionPipe extends BasePipe {
    protected $func;

    public function __contruct($spider, $func) {
        $this->spider = $spider;
        $this->func = $func;
    }

    public function pipe($task) {
        $this->$func($spider, $task);
    }
}