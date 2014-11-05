<?php
namespace ddliu\spider\Pipe;

class CombinedPipe extends BasePipe {
    protected $pipes = array();

    public function __construct() {
        foreach (func_get_args() as $pipe) {
            $this->pipe($pipe);
        }
    }

    public function pipe($pipe) {
        $pipe = BasePipe::makePipe($pipe);
        $this->pipes[] = $pipe;
    }

    public function run($task) {
        foreach ($this->pipes as $pipe) {
            if ($task->isEnded()) {
                break;
            }

            // inject spider
            $pipe->spider = $this->spider;

            $pipe->run($task);
        }
    }
}