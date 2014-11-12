<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;

class IfUrlPipe extends BasePipe {
    protected $realPipe;
    public function __construct($condition, $pipe, $elsePipe = null) {
        $this->realPipe = new IfPipe(function($spider, $task) use ($condition) {
            return preg_match($condition, $task['url']);
        }, $pipe, $elsePipe);
    }

    public function run($spider, $task) {
        $this->realPipe->run($spider, $task);
    }
}