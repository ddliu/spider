<?php
namespace ddliu\spider;

class Spider {
    public function addTask($taskInfo) {

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
        $this->pipes[] = $pipe;
    }
}