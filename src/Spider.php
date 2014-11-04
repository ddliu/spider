<?php
namespace ddliu\spider;
use ddliu\spider\Pipe\PipeInterface;

class Spider {
    protected $pipes = array();
    public function addTask($data) {
        $task = new Task($data);
    }

    public function run() {
        while ($task = $this->scheduler->fetch()) {
            $this->scheduler->remove($task)
            foreach ($this->pipes as $pipe) {
                $pipe($this, $task);
            }
        }
    }

    public function pipe($pipe) {
        $this->pipes[] = $this->makePipe($pipe);
        return $this;
    }

    protected function makePipe($pipe) {
        if ($pipe instanceof PipeInterface) {
            return $pipe;
        } elseif (is_callable($pipe)) {
            return new FunctionPipe($this, $pipe);
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
        if (!$task->isEnded()) {
            $task->
        }
    }
}