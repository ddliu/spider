<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;

class IfPipe extends BasePipe {

    protected $condition;
    protected $pipe;
    protected $elsePipe;


    public function __construct($condition, $pipe, $elsePipe = null) {
        if ($pipe) {
            $pipe = self::makePipe($pipe);
        }

        if ($elsePipe) {
            $elsePipe = self::makePipe($elsePipe);
        }

        $callable = is_callable($condition);
        if (!$callable) {
            $condition = (bool) $condition;
        }

        $this->condition = $condition;
        $this->pipe = $pipe;
        $this->elsePipe = $elsePipe;
    }

    public function run($spider, $task) {
        if ($this->condition === true || (!is_bool($this->condition) && call_user_func($this->condition, $spider, $task))) {
            if ($this->pipe) {
                $this->pipe->run($spider, $task);
            }
        } else {
            if ($this->elsePipe) {
                $this->elsePipe->run($spider, $task);
            }
        }
    }
}