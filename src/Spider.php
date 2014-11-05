<?php
namespace ddliu\spider;
use ddliu\spider\Pipe\PipeInterface;
use ddliu\spider\Pipe\FunctionPipe;

class Spider {
    protected $pipes = array();
    protected $tasks = array();
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
        while($task = array_shift($this->tasks)) {
            $this->process($task);
        }
    }

    public function pipe($pipe) {
        $pipe = $this->makePipe($pipe);
        $pipe->spider = $this;
        $this->pipes[] = $pipe;
        return $this;
    }

    protected function makePipe($pipe) {
        if ($pipe instanceof PipeInterface) {
            return $pipe;
        } elseif (is_callable($pipe)) {
            return new FunctionPipe($pipe);
        } else {
            throw new SpiderException('Invalid pipe');
        }
    }

    protected function process($task) {
        $task->start();
        foreach ($this->pipes as $pipe) {
            $pipe->pipe($task);
            if ($task->isEnded()) {
                break;
            }
        }
    }
}