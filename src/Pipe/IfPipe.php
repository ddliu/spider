<?php
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

        $this->condition = $condition;
        $this->pipe = $pipe;
        $this->elsePipe = $elsePipe;
    }

    public function run($task) {
        if (is_callable($this->condition) && call_user_func($this->condition, $this->spider, $task) ||
            $this->condition) {
            if ($this->pipe) {
                $this->pipe->run($task);
            }
        } else {
            if ($this->elsePipe) {
                $this->elsePipe->run($task);
            }
        }
    }
}