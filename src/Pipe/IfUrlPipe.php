<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;
use ddliu\wildcards\Wildcards;

class IfUrlPipe extends BasePipe {
    protected $realPipe;
    public function __construct($condition, $pipe, $elsePipe = null) {
        $wildcards = new Wildcards($condition);
        $this->realPipe = new IfPipe(function($spider, $task) use ($wildcards) {
            return $wildcards->match($task['url']);
        }, $pipe, $elsePipe);
    }

    public function run($spider, $task) {
        $this->realPipe->run($spider, $task);
    }
}