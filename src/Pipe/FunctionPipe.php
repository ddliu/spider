<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;

/**
 * Quickly create a pipe from a closure. Don't use it directly.
 */
class FunctionPipe extends BasePipe {
    protected $func;

    public function __construct($func) {
        $this->func = $func;
    }

    public function run($spider, $task) {
        call_user_func($this->func, $spider, $task);
    }
}