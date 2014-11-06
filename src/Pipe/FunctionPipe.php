<?php
namespace ddliu\spider\Pipe;

/**
 * Quickly create a pipe from a closure. Don't use it directly.
 */
class FunctionPipe extends BasePipe {
    protected $func;

    public function __construct($func) {
        $this->func = $func;
    }

    public function run($task) {
        call_user_func($this->func, $this->spider, $task);
    }
}