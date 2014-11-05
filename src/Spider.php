<?php
namespace ddliu\spider;
use ddliu\spider\Pipe\PipeInterface;
use ddliu\spider\Pipe\FunctionPipe;
use ddliu\spider\Pipe\CombinedPipe;


class Spider {
    protected $pipes = array();
    protected $tasks = array();
    public function __construct() {
        $this->pipe = new CombinedPipe();
    }
    public function addTask($data) {
        if (!$data instanceof Task) {
            $task = new Task($data);
        } else {
            $task = $data;
        }

        $task->spider = $this;
        $this->tasks[] = $task;

        return $this;
    }

    public function run() {
        // TODO: scheduller
        while($task = array_shift($this->tasks)) {
            $this->process($task);
        }
    }

    public function pipe($pipe) {
        $this->pipe->pipe($pipe);
        return $this;
    }

    protected function process($task) {
        $task->start();
        $this->pipe->run($task);
    }
}