<?php
namespace ddliu\spider\Pipe;

class FunctionPipe extends BasePipe {
    protected $func;

    public function __construct($func) {
        $this->func = $func;
    }

    public function pipe($task) {
        call_user_func($this->func, $this->spider, $task);
    }
}