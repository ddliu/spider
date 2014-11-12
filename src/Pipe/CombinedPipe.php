<?php
/**
 * spider
 * @copyright 2014 Liu Dong <ddliuhb@gmail.com>
 * @license MIT
 */

namespace ddliu\spider\Pipe;

/**
 * Combine pipes
 */
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

        return $this;
    }

    public function run($spider, $task) {
        foreach ($this->pipes as $pipe) {
            if ($task->isEnded()) {
                break;
            }

            $pipe->run($spider, $task);
        }
    }
}